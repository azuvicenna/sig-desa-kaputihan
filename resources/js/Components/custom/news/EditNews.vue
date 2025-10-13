<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue'
import {
    FormControl,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
} from '@/Components/ui/form'
import { Input } from '@/Components/ui/input'
import { toTypedSchema } from '@vee-validate/zod'
import { useForm } from 'vee-validate'
import * as z from 'zod'
import { router } from '@inertiajs/vue3'
import TiptapEditor from '@/Components/custom/news/TipTapEditor.vue';
import { ref, onUnmounted, type Ref } from 'vue';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue
} from '@/Components/ui/select'

const props = defineProps<{
    data: any
}>()

const today = new Date();
today.setHours(0, 0, 0, 0);

const MAX_FILE_SIZE = 2 * 1024 * 1024; // 2 MB
const ACCEPTED_IMAGE_TYPES = ["image/jpeg", "image/jpg", "image/png", "image/webp"];

const formSchema = toTypedSchema(z.object({
    judul: z.string()
        .nonempty('Judul berita wajib diisi.')
        .max(255, 'Judul berita tidak boleh lebih dari 255 karakter.'),
    content: z.string().optional(),
    status: z.enum(['Draf', 'Published'], {
        required_error: 'Status berita wajib diisi.',
    }),
    thumbnail: z
        .instanceof(File, { message: 'Thumbnail wajib diisi.' })
        .refine((file) => file.size <= MAX_FILE_SIZE, `Ukuran file maksimal adalah 2 MB.`)
        .refine((file) => ACCEPTED_IMAGE_TYPES.includes(file.type), "Format file harus .jpg, .jpeg, atau .png")
}));

const { handleSubmit, setErrors, setFieldValue, validateField } = useForm({
    validationSchema: formSchema,
    initialValues: {
        judul: props.data.judul,
        content: props.data.content,
        status: props.data.status,
        thumbnail: props.data.thumbnail
    }
});

const thumbnailPreview = ref<string | null>(`/storage/foto-berita/${props.data.thumbnail}`);

const onFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0];

    if (file) {
        setFieldValue('thumbnail', file);
        validateField('thumbnail');
        thumbnailPreview.value = URL.createObjectURL(file);
    } else {
        setFieldValue('thumbnail', props.data.thumbnail);
        validateField('thumbnail');
        thumbnailPreview.value = `/storage/foto-berita/${props.data.thumbnail}`;
    }
};

onUnmounted(() => {
    if (thumbnailPreview.value) {
        URL.revokeObjectURL(thumbnailPreview.value);
    }
});

const onSubmit = handleSubmit((values) => {
    router.post(route('admin.newsSave'), values, {
        onError: (backendErrors) => {
            setErrors(backendErrors);
        }
    });
});

// multiple upload
interface FileWrapper {
    file: File;
    url: string;
}

const files: Ref<FileWrapper[]> = ref([]);
const isDragging = ref(false);
const fileInput: Ref<HTMLInputElement | null> = ref(null);

const addFiles = (fileList: FileList): void => {
    const newFiles: FileWrapper[] = Array.from(fileList)
        .filter(file => file.type.startsWith('image/'))
        .map(file => ({
            file: file,
            url: URL.createObjectURL(file)
        }));
    files.value.push(...newFiles);
};

const handleFileSelect = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (target.files) addFiles(target.files);
};

const handleDrop = (e: DragEvent) => {
    isDragging.value = false;
    if (e.dataTransfer?.files) addFiles(e.dataTransfer.files);
};

const removeFile = (index: number): void => {
    const fileToRemove = files.value[index];
    URL.revokeObjectURL(fileToRemove.url);
    files.value.splice(index, 1);
};

const handleUpload = (): void => {
    if (files.value.length === 0) {
        alert('Silahkan pilih gambar terlebih dahulu');
        return;
    }

    const formData = new FormData();
    files.value.forEach(wrapper => {
        formData.append('images[]', wrapper.file);
    });

    formData.forEach((value, key) => {
        const file = value as File;
        console.log(`${key}: ${file.name}`);
    });
};

onUnmounted(() => {
    files.value.forEach(file => URL.revokeObjectURL(file.url));
});
</script>

<template>
    <div class="flex-1 overflow-y-auto space-y-4 py-6">
        <form @submit="onSubmit">
            <FormField name="thumbnail">
                <div v-if="thumbnailPreview" class="mt-4 border w-full rounded-md p-2">
                    <p class="text-sm font-medium mb-2">Preview:</p>
                    <div class="flex justify-center">
                        <img :src="thumbnailPreview" alt="Preview Thumbnail"
                            class="w-full max-w-xs h-auto rounded-md" />
                    </div>
                </div>

                <FormItem>
                    <FormLabel>Thumbnail Berita</FormLabel>
                    <FormControl>
                        <Input type="file" accept="image/*" @change="onFileChange" class="file:text-foreground" />
                    </FormControl>
                    <FormMessage />
                </FormItem>
            </FormField>

            <FormField v-slot="{ componentField }" name="judul">
                <FormItem>
                    <FormLabel>Judul Berita</FormLabel>
                    <FormControl>
                        <Input type="text" placeholder="Masukkan judul berita" v-bind="componentField" />
                    </FormControl>
                    <FormMessage />
                </FormItem>
            </FormField>

            <FormField v-slot="{ componentField }" name="content">
                <FormItem>
                    <FormLabel>Konten Berita</FormLabel>
                    <FormControl>
                        <TiptapEditor v-bind="componentField" />
                    </FormControl>
                    <FormMessage />
                </FormItem>
            </FormField>

            <FormField v-slot="{ componentField }" name="status">
                <FormItem>
                    <FormLabel>Status Berita</FormLabel>
                    <Select v-bind="componentField">
                        <FormControl>
                            <SelectTrigger>
                                <SelectValue placeholder="Pilih status berita" />
                            </SelectTrigger>
                        </FormControl>
                        <SelectContent>
                            <SelectItem value="Draf">Draf</SelectItem>
                            <SelectItem value="Published">Published</SelectItem>
                        </SelectContent>
                    </Select>
                    <FormMessage />
                </FormItem>
            </FormField>
        </form>

        <div class="flex justify-end">
            <Button type="submit" class="shadcn-btn edit-btn">Simpan Berita</Button>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto space-y-4 py-6">
        <form @submit.prevent="handleUpload">

            <div class="flex flex-col items-center justify-center p-8 text-center border-2 border-dashed rounded-lg cursor-pointer transition-colors duration-300 ease-in-out"
                :class="isDragging ? 'border-blue-500 bg-blue-50' : 'border-gray-300 bg-gray-50 hover:border-blue-400'"
                @click="fileInput?.click()" @dragenter.prevent="isDragging = true" @dragover.prevent="isDragging = true"
                @dragleave.prevent="isDragging = false" @drop.prevent="handleDrop">
                <input type="file" ref="fileInput" multiple accept="image/*" class="hidden" @change="handleFileSelect">

                <div class="flex flex-col items-center pointer-events-none">
                    <svg class="w-12 h-12 mb-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    <p class="font-semibold text-gray-700">Seret & lepas gambar di sini</p>
                    <p class="mt-1 text-sm text-gray-500">atau <span class="text-blue-600">pilih file</span></p>
                </div>
            </div>

            <div v-if="files.length > 0" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mt-6">
                <div v-for="(file, index) in files" :key="file.url"
                    class="relative group p-2 border border-gray-200 rounded-md bg-white shadow-sm flex items-center justify-center min-h-[100px]">
                    <img :src="file.url" :alt="file.file.name" class="w-20" />
                    <div @click="removeFile(index)"
                        class="absolute top-0 right-0 flex items-center justify-center w-6 h-6 text-white bg-red-500 rounded-full cursor-pointer opacity-0 group-hover:opacity-100 transition-opacity"
                        title="Hapus gambar">
                        &#10005;
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-6">
                <Button type="submit" :disabled="files.length === 0" class="shadcn-btn edit-btn">
                    Simpan Dokumentasi
                </Button>
            </div>

        </form>
    </div>
</template>