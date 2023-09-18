<script setup>
import GuestsLayout from "@/Layouts/GuestsLayout.vue";
import Pagination from "@/Components/Pagination/Pagination.vue";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import FormSection from "@/Components/Forms/FormSection.vue";
import SearchBarWithOptions from "@/Components/SearchBars/SearchBarWithOptions.vue";
import { onMounted, ref } from "vue";
import { parseISO, format } from "date-fns";

import { AdjustmentsHorizontalIcon } from "@heroicons/vue/24/outline";
import FullWidthElement from "@/Components/Layouts/FullWidthElement.vue";
import DefaultElement from "@/Components/Layouts/DefaultElement.vue";
import SectionBorder from "@/Components/Sections/SectionBorder.vue";

defineProps({
    posts: {
        required: false,
    },
});

const posts = [
    {
        title: "Tortor condimentum lacinia quis vel",
        id: 1,
        description:
            "At tempor commodo ullamcorper a lacus vestibulum. Nam libero justo laoreet sit amet cursus. Pharetra vel turpis nunc eget lorem dolor sed viverra ipsum. Risus feugiat in ante metus dictum at. Eleifend donec pretium vulputate sapien. Quisque id diam vel quam elementum pulvinar. Arcu felis bibendum ut tristique et egestas quis ipsum suspendisse. Libero volutpat sed cras ornare arcu dui vivamus arcu felis. Etiam erat velit scelerisque in dictum non consectetur a erat. Ut aliquam purus sit amet luctus venenatis. Tempor orci dapibus ultrices in iaculis nunc.",
        categories: ["Design", "Arts"],
        cover_image: "1.jpg",
    },
    {
        title: "Blandit aliquam etiam erat velit",
        id: 2,
        description:
            "Lacus vestibulum. Nam libero justo laoreet sit amet cursus. Pharetra vel turpis nunc eget lorem dolor sed viverra ipsum. Risus feugiat in ante metus dictum at. Eleifend donec pretium vulputate sapien. Quisque id diam vel quam elementum pulvinar. Arcu felis bibendum ut tristique et egestas quis ipsum suspendisse. Libero volutpat sed cras ornare arcu dui vivamus arcu felis. Etiam erat velit scelerisque in dictum non consectetur a erat. Ut aliquam purus sit amet luctus venenatis. Tempor orci dapibus ultrices in iaculis nunc.",
        categories: ["Design", "Arts"],
        cover_image: "2.jpg",
    },
    {
        title: "Ut tristique et egestas quis ipsum",
        id: 3,
        description:
            "Sommodo ullamcorper a lacus vestibulum. Nam libero justo laoreet sit amet cursus. Pharetra vel turpis nunc eget lorem dolor sed viverra ipsum. Risus feugiat in ante metus dictum at. Eleifend donec pretium vulputate sapien. Quisque id diam vel quam elementum pulvinar. Arcu felis bibendum ut tristique et egestas quis ipsum suspendisse. Libero volutpat sed cras ornare arcu dui vivamus arcu felis. Etiam erat velit scelerisque in dictum non consectetur a erat. Ut aliquam purus sit amet luctus venenatis. Tempor orci dapibus ultrices in iaculis nunc.",
        categories: ["Design", "Arts"],
        cover_image: "3.jpg",
    },
    {
        title: "Magna sit amet purus gravida",
        id: 4,
        description:
            "Scelerisque viverra mauris in aliquam. Gravida quis blandit turpis cursus in hac habitasse platea. Sed nisi lacus sed viverra tellus in hac. Vel eros donec ac odio tempor orci dapibus ultrices. At risus viverra adipiscing at in tellus integer feugiat scelerisque. Augue interdum velit euismod in. Malesuada proin libero nunc consequat interdum varius sit amet mattis. Sapien eget mi proin sed libero enim sed. At quis risus sed vulputate odio ut enim. In fermentum et sollicitudin ac orci. Interdum consectetur libero id faucibus nisl tincidunt. Sed arcu non odio euismod. Non curabitur gravida arcu ac tortor dignissim convallis aenean et. Pharetra massa massa ultricies mi quis hendrerit. Id eu nisl nunc mi ipsum faucibus vitae. Mollis nunc sed id semper risus in hendrerit gravida rutrum. Nunc non blandit massa enim.",
        categories: ["Design", "Arts"],
        cover_image: "4.jpg",
    },
    {
        title: "Diam ut venenatis tellus in",
        id: 5,
        description:
            "Vandit turpis cursus in hac habitasse platea. Sed nisi lacus sed viverra tellus in hac. Vel eros donec ac odio tempor orci dapibus ultrices. At risus viverra adipiscing at in tellus integer feugiat scelerisque. Augue interdum velit euismod in. Malesuada proin libero nunc consequat interdum varius sit amet mattis. Sapien eget mi proin sed libero enim sed. At quis risus sed vulputate odio ut enim. In fermentum et sollicitudin ac orci. Interdum consectetur libero id faucibus nisl tincidunt. Sed arcu non odio euismod. Non curabitur gravida arcu ac tortor dignissim convallis aenean et. Pharetra massa massa ultricies mi quis hendrerit. Id eu nisl nunc mi ipsum faucibus vitae. Mollis nunc sed id semper risus in hendrerit gravida rutrum. Nunc non blandit massa enim.",
        categories: ["Design", "Arts"],
        cover_image: "5.jpg",
    },
    {
        title: "Est ullamcorper eget nulla",
        id: 6,
        description:
            "Surpis cursus in hac habitasse platea. Sed nisi lacus sed viverra tellus in hac. Vel eros donec ac odio tempor orci dapibus ultrices. At risus viverra adipiscing at in tellus integer feugiat scelerisque. Augue interdum velit euismod in. Malesuada proin libero nunc consequat interdum varius sit amet mattis. Sapien eget mi proin sed libero enim sed. At quis risus sed vulputate odio ut enim. In fermentum et sollicitudin ac orci. Interdum consectetur libero id faucibus nisl tincidunt. Sed arcu non odio euismod. Non curabitur gravida arcu ac tortor dignissim convallis aenean et. Pharetra massa massa ultricies mi quis hendrerit. Id eu nisl nunc mi ipsum faucibus vitae. Mollis nunc sed id semper risus in hendrerit gravida rutrum. Nunc non blandit massa enim.",
        categories: ["Design", "Arts"],
        cover_image: "6.jpg",
    },
    {
        title: "Ultricies mi eget mauris pharetra et",
        id: 7,
        description:
            "Platea sed nisi lacus sed viverra tellus in hac. Vel eros donec ac odio tempor orci dapibus ultrices. At risus viverra adipiscing at in tellus integer feugiat scelerisque. Augue interdum velit euismod in. Malesuada proin libero nunc consequat interdum varius sit amet mattis. Sapien eget mi proin sed libero enim sed. At quis risus sed vulputate odio ut enim. In fermentum et sollicitudin ac orci. Interdum consectetur libero id faucibus nisl tincidunt. Sed arcu non odio euismod. Non curabitur gravida arcu ac tortor dignissim convallis aenean et. Pharetra massa massa ultricies mi quis hendrerit. Id eu nisl nunc mi ipsum faucibus vitae. Mollis nunc sed id semper risus in hendrerit gravida rutrum. Nunc non blandit massa enim.",
        categories: ["Design", "Arts"],
        cover_image: "7.jpg",
    },
    {
        title: "Odio tempor orci",
        id: 8,
        description:
            "Vel eros donec ac odio tempor orci dapibus ultrices. At risus viverra adipiscing at in tellus integer feugiat scelerisque. Augue interdum velit euismod in. Malesuada proin libero nunc consequat interdum varius sit amet mattis. Sapien eget mi proin sed libero enim sed. At quis risus sed vulputate odio ut enim. In fermentum et sollicitudin ac orci. Interdum consectetur libero id faucibus nisl tincidunt. Sed arcu non odio euismod. Non curabitur gravida arcu ac tortor dignissim convallis aenean et. Pharetra massa massa ultricies mi quis hendrerit. Id eu nisl nunc mi ipsum faucibus vitae. Mollis nunc sed id semper risus in hendrerit gravida rutrum. Nunc non blandit massa enim.",
        categories: ["Design", "Arts"],
        cover_image: null,
    },
    {
        title: "Tellus orci ac auctor augue mauris augue neque",
        id: 9,
        description:
            "Cursus in hac habitasse platea. Sed nisi lacus sed viverra tellus in hac. Vel eros donec ac odio tempor orci dapibus ultrices. At risus viverra adipiscing at in tellus integer feugiat scelerisque. Augue interdum velit euismod in. Malesuada proin libero nunc consequat interdum varius sit amet mattis. Sapien eget mi proin sed libero enim sed. At quis risus sed vulputate odio ut enim. In fermentum et sollicitudin ac orci. Interdum consectetur libero id faucibus nisl tincidunt. Sed arcu non odio euismod. Non curabitur gravida arcu ac tortor dignissim convallis aenean et. Pharetra massa massa ultricies mi quis hendrerit. Id eu nisl nunc mi ipsum faucibus vitae. Mollis nunc sed id semper risus in hendrerit gravida rutrum. Nunc non blandit massa enim.",
        categories: ["Design", "Arts"],
        cover_image: "9.jpg",
    },
    {
        title: "Placerat orci nulla pellentesque dignissim enim sit amet",
        id: 10,
        description:
            "A arcu cursus vitae congue mauris. Praesent semper feugiat nibh sed. Feugiat in ante metus dictum. Eu consequat ac felis donec et odio pellentesque diam. Sit amet dictum sit amet justo donec enim diam vulputate. Dictum varius duis at consectetur. Bibendum est ultricies integer quis auctor elit sed. Bibendum at varius vel pharetra vel turpis nunc. Faucibus interdum posuere lorem ipsum dolor. Iaculis eu non diam phasellus vestibulum lorem sed. Quam nulla porttitor massa id neque. Eu consequat ac felis donec et odio. Nec ullamcorper sit amet risus nullam eget felis. Tempus egestas sed sed risus pretium quam vulputate dignissim. Massa sapien faucibus et molestie ac. Orci sagittis eu volutpat odio. Montes nascetur ridiculus mus mauris. Non enim praesent elementum facilisis. Rhoncus dolor purus non enim praesent elementum facilisis.",
        categories: ["Design", "Arts", "Event", "Summer", "News", "Travel"],
        cover_image: "10.jpg",
    },
    {
        title: "Purus viverra accumsan in nisl",
        id: 11,
        description:
            "Praesent semper feugiat nibh sed. Feugiat in ante metus dictum. Eu consequat ac felis donec et odio pellentesque diam. Sit amet dictum sit amet justo donec enim diam vulputate. Dictum varius duis at consectetur. Bibendum est ultricies integer quis auctor elit sed. Bibendum at varius vel pharetra vel turpis nunc. Faucibus interdum posuere lorem ipsum dolor. Iaculis eu non diam phasellus vestibulum lorem sed. Quam nulla porttitor massa id neque. Eu consequat ac felis donec et odio. Nec ullamcorper sit amet risus nullam eget felis. Tempus egestas sed sed risus pretium quam vulputate dignissim. Massa sapien faucibus et molestie ac. Orci sagittis eu volutpat odio. Montes nascetur ridiculus mus mauris. Non enim praesent elementum facilisis. Rhoncus dolor purus non enim praesent elementum facilisis.",
        categories: ["Design", "Arts"],
        cover_image: "11.jpg",
    },
    {
        title: "Risus ultricies tristique nulla aliquet enim tortor",
        id: 12,
        description:
            "Feugiat in ante metus dictum. Eu consequat ac felis donec et odio pellentesque diam. Sit amet dictum sit amet justo donec enim diam vulputate. Dictum varius duis at consectetur. Bibendum est ultricies integer quis auctor elit sed. Bibendum at varius vel pharetra vel turpis nunc. Faucibus interdum posuere lorem ipsum dolor. Iaculis eu non diam phasellus vestibulum lorem sed. Quam nulla porttitor massa id neque. Eu consequat ac felis donec et odio. Nec ullamcorper sit amet risus nullam eget felis. Tempus egestas sed sed risus pretium quam vulputate dignissim. Massa sapien faucibus et molestie ac. Orci sagittis eu volutpat odio. Montes nascetur ridiculus mus mauris. Non enim praesent elementum facilisis. Rhoncus dolor purus non enim praesent elementum facilisis.",
        categories: ["Design", "Arts"],
        cover_image: null,
    },
    {
        title: "Non pulvinar neque laoreet suspendisse",
        id: 13,
        description:
            "Consequat ac felis donec et odio pellentesque diam. Sit amet dictum sit amet justo donec enim diam vulputate. Dictum varius duis at consectetur. Bibendum est ultricies integer quis auctor elit sed. Bibendum at varius vel pharetra vel turpis nunc. Faucibus interdum posuere lorem ipsum dolor. Iaculis eu non diam phasellus vestibulum lorem sed. Quam nulla porttitor massa id neque. Eu consequat ac felis donec et odio. Nec ullamcorper sit amet risus nullam eget felis. Tempus egestas sed sed risus pretium quam vulputate dignissim. Massa sapien faucibus et molestie ac. Orci sagittis eu volutpat odio. Montes nascetur ridiculus mus mauris. Non enim praesent elementum facilisis. Rhoncus dolor purus non enim praesent elementum facilisis.",
        categories: ["Design", "Arts"],
        cover_image: "13.jpg",
    },
    {
        title: "Senectus et netus et malesuada fames",
        id: 14,
        description:
            "Felis donec et odio pellentesque diam. Sit amet dictum sit amet justo donec enim diam vulputate. Dictum varius duis at consectetur. Bibendum est ultricies integer quis auctor elit sed. Bibendum at varius vel pharetra vel turpis nunc. Faucibus interdum posuere lorem ipsum dolor. Iaculis eu non diam phasellus vestibulum lorem sed. Quam nulla porttitor massa id neque. Eu consequat ac felis donec et odio. Nec ullamcorper sit amet risus nullam eget felis. Tempus egestas sed sed risus pretium quam vulputate dignissim. Massa sapien faucibus et molestie ac. Orci sagittis eu volutpat odio. Montes nascetur ridiculus mus mauris. Non enim praesent elementum facilisis. Rhoncus dolor purus non enim praesent elementum facilisis.",
        categories: ["Design", "Arts"],
        cover_image: "14.jpg",
    },
    {
        title: "Commodo elit at imperdiet dui accumsan",
        id: 15,
        description:
            "Pellentesque diam. Sit amet dictum sit amet justo donec enim diam vulputate. Dictum varius duis at consectetur. Bibendum est ultricies integer quis auctor elit sed. Bibendum at varius vel pharetra vel turpis nunc. Faucibus interdum posuere lorem ipsum dolor. Iaculis eu non diam phasellus vestibulum lorem sed. Quam nulla porttitor massa id neque. Eu consequat ac felis donec et odio. Nec ullamcorper sit amet risus nullam eget felis. Tempus egestas sed sed risus pretium quam vulputate dignissim. Massa sapien faucibus et molestie ac. Orci sagittis eu volutpat odio. Montes nascetur ridiculus mus mauris. Non enim praesent elementum facilisis. Rhoncus dolor purus non enim praesent elementum facilisis.",
        categories: ["Design", "Arts"],
        cover_image: "15.jpg",
    },
    {
        title: "Eu lobortis elementum",
        id: 16,
        description:
            "Amet dictum sit amet justo donec enim diam vulputate. Dictum varius duis at consectetur. Bibendum est ultricies integer quis auctor elit sed. Bibendum at varius vel pharetra vel turpis nunc. Faucibus interdum posuere lorem ipsum dolor. Iaculis eu non diam phasellus vestibulum lorem sed. Quam nulla porttitor massa id neque. Eu consequat ac felis donec et odio. Nec ullamcorper sit amet risus nullam eget felis. Tempus egestas sed sed risus pretium quam vulputate dignissim. Massa sapien faucibus et molestie ac. Orci sagittis eu volutpat odio. Montes nascetur ridiculus mus mauris. Non enim praesent elementum facilisis. Rhoncus dolor purus non enim praesent elementum facilisis.",
        categories: ["Design", "Arts"],
        cover_image: "16.jpg",
    },
    {
        title: "Nibh tellus molestie",
        id: 17,
        description:
            "Justo donec enim diam vulputate. Dictum varius duis at consectetur. Bibendum est ultricies integer quis auctor elit sed. Bibendum at varius vel pharetra vel turpis nunc. Faucibus interdum posuere lorem ipsum dolor. Iaculis eu non diam phasellus vestibulum lorem sed. Quam nulla porttitor massa id neque. Eu consequat ac felis donec et odio. Nec ullamcorper sit amet risus nullam eget felis. Tempus egestas sed sed risus pretium quam vulputate dignissim. Massa sapien faucibus et molestie ac. Orci sagittis eu volutpat odio. Montes nascetur ridiculus mus mauris. Non enim praesent elementum facilisis. Rhoncus dolor purus non enim praesent elementum facilisis.",
        categories: ["Design", "Arts"],
        cover_image: "17.jpg",
    },
    {
        title: "Dui vivamus arcu felis",
        id: 18,
        description:
            "Dictum varius duis at consectetur. Bibendum est ultricies integer quis auctor elit sed. Bibendum at varius vel pharetra vel turpis nunc. Faucibus interdum posuere lorem ipsum dolor. Iaculis eu non diam phasellus vestibulum lorem sed. Quam nulla porttitor massa id neque. Eu consequat ac felis donec et odio. Nec ullamcorper sit amet risus nullam eget felis. Tempus egestas sed sed risus pretium quam vulputate dignissim. Massa sapien faucibus et molestie ac. Orci sagittis eu volutpat odio. Montes nascetur ridiculus mus mauris. Non enim praesent elementum facilisis. Rhoncus dolor purus non enim praesent elementum facilisis.",
        categories: ["Design", "Arts"],
        cover_image: "18.jpg",
    },
    {
        title: "Pellentesque dignissim enim sit",
        id: 19,
        description:
            "Bibendum est ultricies integer quis auctor elit sed. Bibendum at varius vel pharetra vel turpis nunc. Faucibus interdum posuere lorem ipsum dolor. Iaculis eu non diam phasellus vestibulum lorem sed. Quam nulla porttitor massa id neque. Eu consequat ac felis donec et odio. Nec ullamcorper sit amet risus nullam eget felis. Tempus egestas sed sed risus pretium quam vulputate dignissim. Massa sapien faucibus et molestie ac. Orci sagittis eu volutpat odio. Montes nascetur ridiculus mus mauris. Non enim praesent elementum facilisis. Rhoncus dolor purus non enim praesent elementum facilisis.",
        categories: ["Design", "Arts"],
        cover_image: "19.jpg",
    },
    {
        title: "Risus viverra adipiscing ",
        id: 20,
        description:
            "Faucibus interdum posuere lorem ipsum dolor. Iaculis eu non diam phasellus vestibulum lorem sed. Quam nulla porttitor massa id neque. Eu consequat ac felis donec et odio. Nec ullamcorper sit amet risus nullam eget felis. Tempus egestas sed sed risus pretium quam vulputate dignissim. Massa sapien faucibus et molestie ac. Orci sagittis eu volutpat odio. Montes nascetur ridiculus mus mauris. Non enim praesent elementum facilisis. Rhoncus dolor purus non enim praesent elementum facilisis.",
        categories: ["Design", "Arts"],
        cover_image: "20.jpg",
    },
    {
        title: "Senectus et netus et malesuada fames",
        id: 21,
        description:
            "Felis donec et odio pellentesque diam. Sit amet dictum sit amet justo donec enim diam vulputate. Dictum varius duis at consectetur. Bibendum est ultricies integer quis auctor elit sed. Bibendum at varius vel pharetra vel turpis nunc. Faucibus interdum posuere lorem ipsum dolor. Iaculis eu non diam phasellus vestibulum lorem sed. Quam nulla porttitor massa id neque. Eu consequat ac felis donec et odio. Nec ullamcorper sit amet risus nullam eget felis. Tempus egestas sed sed risus pretium quam vulputate dignissim. Massa sapien faucibus et molestie ac. Orci sagittis eu volutpat odio. Montes nascetur ridiculus mus mauris. Non enim praesent elementum facilisis. Rhoncus dolor purus non enim praesent elementum facilisis.",
        categories: ["Design", "Arts"],
        cover_image: "21.jpg",
    },
    {
        title: "Commodo elit at imperdiet dui accumsan",
        id: 22,
        description:
            "Pellentesque diam. Sit amet dictum sit amet justo donec enim diam vulputate. Dictum varius duis at consectetur. Bibendum est ultricies integer quis auctor elit sed. Bibendum at varius vel pharetra vel turpis nunc. Faucibus interdum posuere lorem ipsum dolor. Iaculis eu non diam phasellus vestibulum lorem sed. Quam nulla porttitor massa id neque. Eu consequat ac felis donec et odio. Nec ullamcorper sit amet risus nullam eget felis. Tempus egestas sed sed risus pretium quam vulputate dignissim. Massa sapien faucibus et molestie ac. Orci sagittis eu volutpat odio. Montes nascetur ridiculus mus mauris. Non enim praesent elementum facilisis. Rhoncus dolor purus non enim praesent elementum facilisis.",
        categories: ["Design", "Arts"],
        cover_image: null,
    },
    {
        title: "Diam ut venenatis tellus in",
        id: 23,
        description:
            "Vandit turpis cursus in hac habitasse platea. Sed nisi lacus sed viverra tellus in hac. Vel eros donec ac odio tempor orci dapibus ultrices. At risus viverra adipiscing at in tellus integer feugiat scelerisque. Augue interdum velit euismod in. Malesuada proin libero nunc consequat interdum varius sit amet mattis. Sapien eget mi proin sed libero enim sed. At quis risus sed vulputate odio ut enim. In fermentum et sollicitudin ac orci. Interdum consectetur libero id faucibus nisl tincidunt. Sed arcu non odio euismod. Non curabitur gravida arcu ac tortor dignissim convallis aenean et. Pharetra massa massa ultricies mi quis hendrerit. Id eu nisl nunc mi ipsum faucibus vitae. Mollis nunc sed id semper risus in hendrerit gravida rutrum. Nunc non blandit massa enim.",
        categories: ["Design", "Arts"],
        cover_image: null,
    },
    {
        title: "Est ullamcorper eget nulla",
        id: 24,
        description:
            "Surpis cursus in hac habitasse platea. Sed nisi lacus sed viverra tellus in hac. Vel eros donec ac odio tempor orci dapibus ultrices. At risus viverra adipiscing at in tellus integer feugiat scelerisque. Augue interdum velit euismod in. Malesuada proin libero nunc consequat interdum varius sit amet mattis. Sapien eget mi proin sed libero enim sed. At quis risus sed vulputate odio ut enim. In fermentum et sollicitudin ac orci. Interdum consectetur libero id faucibus nisl tincidunt. Sed arcu non odio euismod. Non curabitur gravida arcu ac tortor dignissim convallis aenean et. Pharetra massa massa ultricies mi quis hendrerit. Id eu nisl nunc mi ipsum faucibus vitae. Mollis nunc sed id semper risus in hendrerit gravida rutrum. Nunc non blandit massa enim.",
        categories: ["Design", "Arts"],
        cover_image: "24.jpg",
    },
    {
        title: "Ultricies mi eget mauris pharetra et",
        id: 25,
        description:
            "Platea sed nisi lacus sed viverra tellus in hac. Vel eros donec ac odio tempor orci dapibus ultrices. At risus viverra adipiscing at in tellus integer feugiat scelerisque. Augue interdum velit euismod in. Malesuada proin libero nunc consequat interdum varius sit amet mattis. Sapien eget mi proin sed libero enim sed. At quis risus sed vulputate odio ut enim. In fermentum et sollicitudin ac orci. Interdum consectetur libero id faucibus nisl tincidunt. Sed arcu non odio euismod. Non curabitur gravida arcu ac tortor dignissim convallis aenean et. Pharetra massa massa ultricies mi quis hendrerit. Id eu nisl nunc mi ipsum faucibus vitae. Mollis nunc sed id semper risus in hendrerit gravida rutrum. Nunc non blandit massa enim.",
        categories: ["Design", "Arts"],
        cover_image: "25.jpg",
    },
    {
        title: "Tellus orci ac auctor augue mauris augue neque",
        id: 26,
        description:
            "Cursus in hac habitasse platea. Sed nisi lacus sed viverra tellus in hac. Vel eros donec ac odio tempor orci dapibus ultrices. At risus viverra adipiscing at in tellus integer feugiat scelerisque. Augue interdum velit euismod in. Malesuada proin libero nunc consequat interdum varius sit amet mattis. Sapien eget mi proin sed libero enim sed. At quis risus sed vulputate odio ut enim. In fermentum et sollicitudin ac orci. Interdum consectetur libero id faucibus nisl tincidunt. Sed arcu non odio euismod. Non curabitur gravida arcu ac tortor dignissim convallis aenean et. Pharetra massa massa ultricies mi quis hendrerit. Id eu nisl nunc mi ipsum faucibus vitae. Mollis nunc sed id semper risus in hendrerit gravida rutrum. Nunc non blandit massa enim.",
        categories: ["Design", "Arts"],
        cover_image: "26.jpg",
    },
    {
        title: "Placerat orci nulla pellentesque dignissim enim sit amet",
        id: 27,
        description:
            "A arcu cursus vitae congue mauris. Praesent semper feugiat nibh sed. Feugiat in ante metus dictum. Eu consequat ac felis donec et odio pellentesque diam. Sit amet dictum sit amet justo donec enim diam vulputate. Dictum varius duis at consectetur. Bibendum est ultricies integer quis auctor elit sed. Bibendum at varius vel pharetra vel turpis nunc. Faucibus interdum posuere lorem ipsum dolor. Iaculis eu non diam phasellus vestibulum lorem sed. Quam nulla porttitor massa id neque. Eu consequat ac felis donec et odio. Nec ullamcorper sit amet risus nullam eget felis. Tempus egestas sed sed risus pretium quam vulputate dignissim. Massa sapien faucibus et molestie ac. Orci sagittis eu volutpat odio. Montes nascetur ridiculus mus mauris. Non enim praesent elementum facilisis. Rhoncus dolor purus non enim praesent elementum facilisis.",
        categories: ["Design", "Arts"],
        cover_image: "27.jpg",
    },
    {
        title: "Purus viverra accumsan in nisl",
        id: 28,
        description:
            "Praesent semper feugiat nibh sed. Feugiat in ante metus dictum. Eu consequat ac felis donec et odio pellentesque diam. Sit amet dictum sit amet justo donec enim diam vulputate. Dictum varius duis at consectetur. Bibendum est ultricies integer quis auctor elit sed. Bibendum at varius vel pharetra vel turpis nunc. Faucibus interdum posuere lorem ipsum dolor. Iaculis eu non diam phasellus vestibulum lorem sed. Quam nulla porttitor massa id neque. Eu consequat ac felis donec et odio. Nec ullamcorper sit amet risus nullam eget felis. Tempus egestas sed sed risus pretium quam vulputate dignissim. Massa sapien faucibus et molestie ac. Orci sagittis eu volutpat odio. Montes nascetur ridiculus mus mauris. Non enim praesent elementum facilisis. Rhoncus dolor purus non enim praesent elementum facilisis.",
        categories: ["Design", "Arts"],
        cover_image: null,
    },
    {
        title: "Risus ultricies tristique nulla aliquet enim tortor",
        id: 29,
        description:
            "Feugiat in ante metus dictum. Eu consequat ac felis donec et odio pellentesque diam. Sit amet dictum sit amet justo donec enim diam vulputate. Dictum varius duis at consectetur. Bibendum est ultricies integer quis auctor elit sed. Bibendum at varius vel pharetra vel turpis nunc. Faucibus interdum posuere lorem ipsum dolor. Iaculis eu non diam phasellus vestibulum lorem sed. Quam nulla porttitor massa id neque. Eu consequat ac felis donec et odio. Nec ullamcorper sit amet risus nullam eget felis. Tempus egestas sed sed risus pretium quam vulputate dignissim. Massa sapien faucibus et molestie ac. Orci sagittis eu volutpat odio. Montes nascetur ridiculus mus mauris. Non enim praesent elementum facilisis. Rhoncus dolor purus non enim praesent elementum facilisis.",
        categories: ["Design", "Arts"],
        cover_image: "29.jpg",
    },
    {
        title: "Non pulvinar neque laoreet suspendisse",
        id: 30,
        description:
            "Consequat ac felis donec et odio pellentesque diam. Sit amet dictum sit amet justo donec enim diam vulputate. Dictum varius duis at consectetur. Bibendum est ultricies integer quis auctor elit sed. Bibendum at varius vel pharetra vel turpis nunc. Faucibus interdum posuere lorem ipsum dolor. Iaculis eu non diam phasellus vestibulum lorem sed. Quam nulla porttitor massa id neque. Eu consequat ac felis donec et odio. Nec ullamcorper sit amet risus nullam eget felis. Tempus egestas sed sed risus pretium quam vulputate dignissim. Massa sapien faucibus et molestie ac. Orci sagittis eu volutpat odio. Montes nascetur ridiculus mus mauris. Non enim praesent elementum facilisis. Rhoncus dolor purus non enim praesent elementum facilisis.",
        categories: ["Design", "Arts"],
        cover_image: "30.jpg",
    },
    {
        title: "Dictum varius neque laoreet suspendisse",
        id: 32,
        description:
            "Consequat ac felis donec et odio pellentesque diam. Sit amet dictum sit amet justo donec enim diam vulputate. Dictum varius duis at consectetur. Bibendum est ultricies integer quis auctor elit sed. Bibendum at varius vel pharetra vel turpis nunc. Faucibus interdum posuere lorem ipsum dolor. Iaculis eu non diam phasellus vestibulum lorem sed. Quam nulla porttitor massa id neque. Eu consequat ac felis donec et odio. Nec ullamcorper sit amet risus nullam eget felis. Tempus egestas sed sed risus pretium quam vulputate dignissim. Massa sapien faucibus et molestie ac. Orci sagittis eu volutpat odio. Montes nascetur ridiculus mus mauris. Non enim praesent elementum facilisis. Rhoncus dolor purus non enim praesent elementum facilisis.",
        categories: ["Design", "Arts"],
        cover_image: null,
    },
    {
        title: "Non pulvinar neque laoreet suspendisse",
        id: 33,
        description:
            "Consequat ac felis donec et odio pellentesque diam. Sit amet dictum sit amet justo donec enim diam vulputate. Dictum varius duis at consectetur. Bibendum est ultricies integer quis auctor elit sed. Bibendum at varius vel pharetra vel turpis nunc. Faucibus interdum posuere lorem ipsum dolor. Iaculis eu non diam phasellus vestibulum lorem sed. Quam nulla porttitor massa id neque. Eu consequat ac felis donec et odio. Nec ullamcorper sit amet risus nullam eget felis. Tempus egestas sed sed risus pretium quam vulputate dignissim. Massa sapien faucibus et molestie ac. Orci sagittis eu volutpat odio. Montes nascetur ridiculus mus mauris. Non enim praesent elementum facilisis. Rhoncus dolor purus non enim praesent elementum facilisis.",
        categories: ["Design", "Arts"],
        cover_image: null,
    },
];
//
</script>
<template>
    <GuestsLayout>
        <Head title="Blog" />
        <template #header>
            <div class="myPrimaryMainPageHeaderParagraph">
                <h1 class="myPrimaryMainPageHeaderNotLoggedIn">Blog</h1>
                <p class="myPrimaryMainPageParagraphNotLoggedIn">
                    Experience elegance with a minimalistic Admin Panel. <br />
                    Multiple Security Layers, advanced Team Management, Blog and
                    Media Library.
                    <br />
                    <a
                        class="text-myPrimaryLinkColor"
                        href="https://github.com/qaiswardag/myissue-admin"
                        target="_blank"
                    >
                        Visit the project's GitHub repository.
                    </a>
                </p>
            </div>
        </template>

        <FullWidthElement :descriptionArea="true" class="bg-gray-50">
            <template #title>Latest Articles</template>
            <template #description>
                Minimalist Admin Panel empowered with advanced Team Management
                capabilities, Blog, and a beautiful Media Library. Offers
                control and efficiency. Let users and teams elevate their
                administrative abilities to new heights. Designed to streamline
                operations and enhance productivity.
            </template>
            <template #content>
                <ul
                    role="list"
                    class="grid grid-cols-[repeat(auto-fit,minmax(14rem,1fr))] gap-y-12 gap-x-4"
                >
                    <li
                        v-for="post in posts"
                        :key="post.id"
                        class="whitespace-pre-line flex-1 bg-gray-100 h-auto rounded pb-12"
                    >
                        <img
                            v-if="post.cover_image !== null"
                            :src="`/app-images/blog/${post.cover_image}`"
                            :alt="post.title"
                            class="pointer-events-none object-cover group-hover:opacity-75 cursor-pointer"
                        />

                        <div class="px-2 pb-2 mt-2">
                            <ul
                                class="flex flex-wrap gap-y-0 gap-x-2 items-center"
                            >
                                <li
                                    v-for="category in post.categories"
                                    :key="category"
                                    class="myPrimaryParagraph leading-4 font-medium cursor-pointer flex-none"
                                >
                                    <span class="text-[10px] uppercase">
                                        {{ category }}
                                    </span>
                                </li>
                            </ul>
                            <p class="myPrimaryParagraph font-medium mt-2 mb-2">
                                {{ post.title }}
                            </p>
                            <p class="myPrimaryParagraph text-xs">
                                {{ post.description.slice(0, 55) }}..
                            </p>
                            <p
                                class="myPrimaryParagraph font-medium mt-2 mb-2 text-[10px] text-myPrimaryMediumGrayColor"
                            >
                                Updated:
                                {{
                                    format(
                                        parseISO("2023-06-03 21:10:29"),
                                        "dd/MM/yyyy HH:mm"
                                    )
                                }}
                            </p>
                        </div>
                    </li>
                </ul>
            </template>
        </FullWidthElement>
    </GuestsLayout>
</template>
