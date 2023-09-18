<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use PhpParser\Node\Expr\AssignOp\Concat;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize("superadmin-can-read");

        $searchQuery = $request->input("search_query");

        // Check $searchQuery is an array
        // If it is, the implode function joins the elements of the array into a comma-separated string
        if (is_array($searchQuery)) {
            $searchQuery = implode(",", $searchQuery);
        }

        $users = User::latest();

        $users->when($request->filled("selected_category"), function (
            $query
        ) use ($request, $searchQuery) {
            $selectedCategory = $request->input("selected_category");

            if ($selectedCategory === "id") {
                // Check if search_query is null or empty and selected_category is ID
                if ($searchQuery === null || trim($searchQuery) === "") {
                    // No search query provided, show all results
                    return;
                }
                // Convert $searchQuery to an array of IDs using the explode function
                $idArray = explode(",", $searchQuery);
                // filter by user ID
                $query->whereIn("id", $idArray);
            }

            if ($selectedCategory === "name") {
                // filter by user name
                $query->where(function ($subQuery) use (
                    $request,
                    $searchQuery
                ) {
                    $subQuery
                        ->where("first_name", "LIKE", "%" . $searchQuery . "%")
                        ->orWhere("last_name", "LIKE", "%" . $searchQuery . "%")
                        ->orWhere(
                            DB::raw("CONCAT(first_name, ' ', last_name)"),
                            "LIKE",
                            "%" . $searchQuery . "%"
                        );
                });
            }

            if ($selectedCategory === "email") {
                // filter by user email
                $query->where("email", "LIKE", "%" . $searchQuery . "%");
            }
        });

        $users = $users->paginate(10);

        $users->appends($request->all());

        return Inertia::render("Superadmin/Users/Index", [
            "users" => $users,
            "results" => $users->total(),
            "oldInput" => [
                "search_query" => $request->input("search_query"),
                "selected_category" => $request->input("selected_category"),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->authorize("superadmin-can-destroy");

        $user->delete();

        return redirect()
            ->route("admin.users")
            ->with("success", "Successfully deleted User with id: {$user->id}");
    }
}
