<script setup lang="ts">
import { useEditor, EditorContent } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';
import { watch } from 'vue';

const props = withDefaults(defineProps<{
    modelValue?: string;
}>(), {
    modelValue: '',
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void;
}>();

const editor = useEditor({
    extensions: [
        StarterKit.configure(),
    ],
    content: props.modelValue,
    editorProps: {
        attributes: {
            class: 'min-h-[150px] w-full rounded-b-md border-x border-b border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50 prose max-w-none',
        },
    },
    onUpdate: ({ editor }) => {
        const html = editor.getHTML();
        emit('update:modelValue', html);
    },
});

watch(() => props.modelValue, (newValue) => {
    if (editor.value && editor.value.getHTML() !== newValue) {
        editor.value.commands.setContent(newValue);
    }
});
</script>

<template>
    <div class="editor-container rounded-md border border-input">
        <div v-if="editor"
            class="toolbar flex items-center gap-2 p-2 border-b border-input bg-transparent rounded-t-md">
            <button type="button" @click="editor.chain().focus().toggleBold().run()"
                :disabled="!editor.can().chain().focus().toggleBold().run()"
                :class="{ 'is-active': editor.isActive('bold') }">
                Bold
            </button>

            <button type="button" @click="editor.chain().focus().toggleItalic().run()"
                :disabled="!editor.can().chain().focus().toggleItalic().run()"
                :class="{ 'is-active': editor.isActive('italic') }">
                Italic
            </button>

            <button type="button" @click="editor.chain().focus().toggleStrike().run()"
                :disabled="!editor.can().chain().focus().toggleStrike().run()"
                :class="{ 'is-active': editor.isActive('strike') }">
                Strike
            </button>

            <button type="button" @click="editor.chain().focus().toggleBulletList().run()"
                :class="{ 'is-active': editor.isActive('bulletList') }">
                List
            </button>

            <button type="button" @click="editor.chain().focus().toggleOrderedList().run()"
                :class="{ 'is-active': editor.isActive('orderedList') }">
                Numbered List
            </button>
        </div>

        <EditorContent :editor="editor" />
    </div>
</template>

<style>
.editor-container .toolbar {
    display: flex;
    flex-wrap: wrap;
}

.editor-container .toolbar button {
    padding: 0.25rem 0.6rem;
    border-radius: 5px;
    border: 1px solid transparent;
    background-color: transparent;
    cursor: pointer;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.2s;
}

.editor-container .toolbar button:hover {
    background-color: #f1f1f1;
    border-color: #ddd;
}

.editor-container .toolbar button.is-active {
    background-color: #e0e0e0;
    color: #333;
}

.editor-container .toolbar button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.editor-container .ProseMirror:focus {
    outline: none;
}
</style>