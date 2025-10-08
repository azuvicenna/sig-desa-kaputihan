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
import { ref, onUnmounted } from 'vue';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue
} from '@/Components/ui/select'

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
        judul: undefined,
        content: undefined,
        status: 'Draf',
        thumbnail: undefined
    }
});

const thumbnailPreview = ref<string | null>(null);

const onFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0];

    if (file) {
        setFieldValue('thumbnail', file);
        validateField('thumbnail');
        thumbnailPreview.value = URL.createObjectURL(file);
    } else {
        setFieldValue('thumbnail', undefined);
        validateField('thumbnail');
        thumbnailPreview.value = null;
    }
};

onUnmounted(() => {
    if (thumbnailPreview.value) {
        URL.revokeObjectURL(thumbnailPreview.value);
    }
});

const onSubmit = handleSubmit((values) => {
    router.post(route('admin.categorySave'), values, {
        onError: (backendErrors) => {
            setErrors(backendErrors);
        }
    });
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
            <Button type="submit" form="add-schedule-form" class="shadcn-btn edit-btn">Simpan Berita</Button>
        </div>
    </div>
</template>