<?php

namespace App\Actions\Jetstream;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Contracts\UpdatesTeamNames;

class UpdateTeamName implements UpdatesTeamNames
{
    /**
     * Validate and update the given team's name.
     *
     * @param  array<string, string>  $input
     * @var \Illuminate\Validation\Validator $validator
     */
    public function update(User $user, Team $team, array $input)
    {
        Gate::forUser($user)->authorize("update", $team);

        $validator = Validator::make($input, [
            "name" => ["required", "string", "max:255"],
            "public" => ["boolean"],

            "cover_image_original" => [
                "required",
                "nullable",
                "string",
                "max:255",
            ],
            // "cover_image_thumbnail" => [
            //     "required",
            //     "nullable",
            //     "string",
            //     "max:255",
            // ],
            // "cover_image_medium" => [
            //     "required",
            //     "nullable",
            //     "string",
            //     "max:255",
            // ],
            // "cover_image_large" => [
            //     "required",
            //     "nullable",
            //     "string",
            //     "max:255",
            // ],

            "logo_original" => ["required", "nullable", "string", "max:255"],
            // "logo_thumbnail" => ["required", "nullable", "string", "max:255"],
            // "logo_medium" => ["required", "nullable", "string", "max:255"],
            // "logo_large" => ["required", "nullable", "string", "max:255"],
        ]);

        // if validator fails
        if ($validator->fails()) {
            return back()
                ->withErrors($validator, "updateTeam") // Pass validation errors to the session
                ->with(
                    "error",
                    "Please complete all fields correctly to proceed."
                );
        }

        $team
            ->forceFill([
                "name" => $input["name"],
                "public" => $input["public"],

                "cover_image_original" => $input["cover_image_original"],
                "cover_image_thumbnail" => $input["cover_image_thumbnail"],
                "cover_image_medium" => $input["cover_image_medium"],
                "cover_image_large" => $input["cover_image_large"],

                "logo_original" => $input["logo_original"],
                "logo_thumbnail" => $input["logo_thumbnail"],
                "logo_medium" => $input["logo_medium"],
                "logo_large" => $input["logo_large"],
            ])
            ->save();
    }
}
