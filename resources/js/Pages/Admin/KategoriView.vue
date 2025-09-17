<script setup>
import { ref, h, onMounted, reactive } from "vue";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import DataTable from "@/Components/ui/datatable/DataTable.vue";
import DataTableColumnHeader from "@/Components/ui/datatable/DataTableColumnHeader.vue";
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from "@/Components/ui/dropdown-menu";
import { Button } from "@/Components/ui/button";
import { Plus, MoreVertical, Pencil, Trash2 } from "lucide-vue-next";
import Card from "@/Components/ui/card/Card.vue";
import CardContent from "@/Components/ui/card/CardContent.vue";
import CardHeader from "@/Components/ui/card/CardHeader.vue";
import CardTitle from "@/Components/ui/card/CardTitle.vue";
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from "@/Components/ui/dialog";
import { Label } from "@/Components/ui/label";
import { Input } from "@/Components/ui/input";
import { Textarea } from "@/Components/ui/textarea";
import { toast } from "vue-sonner";
import { confirmModal } from "@/Components/ui/confirmation-dialog";
import { Head } from "@inertiajs/vue3";

const breadcrumbs = [{ title: "Dashboard", href: "/dashboard" }, { title: "Kategori" }];

const data = ref([]);
const filteredData = ref([]);
const isLoading = ref(true);
const searchQuery = ref("");
const isDialogOpen = ref(false);
const editingItem = ref(null);

const formData = reactive({
  nama: "",
  slug: "",
  deskripsi: "",
});

// Fetch data from API
const fetchKategoriData = async () => {
  isLoading.value = true;
  try {
    const response = await fetch("/api/kategori");
    const result = await response.json();
    data.value = Array.isArray(result) ? result : [];
    filteredData.value = data.value;
  } catch (error) {
    toast.error("Gagal memuat data kategori");
    console.error(error);
    data.value = [];
    filteredData.value = [];
  } finally {
    isLoading.value = false;
  }
};

const applySearch = () => {
  if (!searchQuery.value.trim()) {
    filteredData.value = data.value;
    return;
  }
  filteredData.value = data.value.filter((item) =>
    item.nama.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
};

const generateSlug = (name) => {
  return name
    .toLowerCase()
    .replace(/[^\w ]+/g, "")
    .replace(/ +/g, "-");
};

const openDialog = (item = null) => {
  editingItem.value = item;
  if (item) {
    Object.assign(formData, item);
  } else {
    Object.keys(formData).forEach((k) => (formData[k] = ""));
  }
  isDialogOpen.value = true;
};

const saveItem = async () => {
  try {
    // Auto generate slug from name
    if (!formData.slug && formData.nama) {
      formData.slug = generateSlug(formData.nama);
    }

    let response;
    if (editingItem.value) {
      response = await fetch(`/api/kategori/${editingItem.value.id}`, {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(formData),
      });
    } else {
      response = await fetch("/api/kategori", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(formData),
      });
    }

    if (response.ok) {
      toast.success(
        editingItem.value
          ? "Kategori berhasil diperbarui"
          : "Kategori berhasil ditambahkan"
      );
      await fetchKategoriData();
      applySearch();
      isDialogOpen.value = false;
    } else {
      const error = await response.json();
      toast.error(error.message || "Gagal menyimpan kategori");
    }
  } catch (error) {
    toast.error("Gagal menyimpan kategori");
    console.error(error);
  }
};

const handleDelete = async (item) => {
  if (!(await confirmModal("Apakah yakin ingin menghapus kategori ini?", item.nama)))
    return;
  try {
    const response = await fetch(`/api/kategori/${item.id}`, {
      method: "DELETE",
    });
    if (response.ok) {
      toast.success("Kategori berhasil dihapus");
      await fetchKategoriData();
      applySearch();
    } else {
      toast.error("Gagal menghapus kategori");
    }
  } catch (error) {
    toast.error("Gagal menghapus kategori");
    console.error(error);
  }
};

onMounted(fetchKategoriData);

const columns = [
  {
    id: "nomor",
    header: "No",
    cell: ({ row }) => h("div", {}, row.index + 1),
  },
  {
    accessorKey: "nama",
    header: ({ column }) => h(DataTableColumnHeader, { column, title: "Nama" }),
    cell: ({ row }) => h("div", { class: "font-medium" }, row.getValue("nama")),
  },
  {
    accessorKey: "slug",
    header: ({ column }) => h(DataTableColumnHeader, { column, title: "Slug" }),
    cell: ({ row }) => h("div", { class: "text-muted-foreground" }, row.getValue("slug")),
  },
  {
    accessorKey: "deskripsi",
    header: ({ column }) => h(DataTableColumnHeader, { column, title: "Deskripsi" }),
    cell: ({ row }) =>
      h("div", { class: "max-w-xs truncate" }, row.getValue("deskripsi") || "-"),
  },
  {
    accessorKey: "created_at",
    header: ({ column }) => h(DataTableColumnHeader, { column, title: "Dibuat" }),
    cell: ({ row }) => {
      const date = row.getValue("created_at");
      return h("div", {}, date ? new Date(date).toLocaleDateString("id-ID") : "-");
    },
  },
  {
    id: "actions",
    header: "Aksi",
    cell: ({ row }) =>
      h(
        DropdownMenu,
        {},
        {
          default: () => [
            h(
              DropdownMenuTrigger,
              {},
              {
                default: () =>
                  h(
                    Button,
                    {
                      variant: "outline",
                      class: "h-8 w-8 p-0",
                    },
                    {
                      default: () => h(MoreVertical, { class: "h-4 w-4" }),
                    }
                  ),
              }
            ),
            h(
              DropdownMenuContent,
              {},
              {
                default: () => [
                  h(
                    DropdownMenuItem,
                    {
                      onClick: () => openDialog(row.original),
                      class: "flex items-center gap-2",
                    },
                    {
                      default: () => [h(Pencil, { class: "h-4 w-4" }), "Edit"],
                    }
                  ),
                  h(
                    DropdownMenuItem,
                    {
                      onClick: () => handleDelete(row.original),
                      class: "text-red-600 flex items-center gap-2",
                    },
                    {
                      default: () => [h(Trash2, { class: "h-4 w-4" }), "Hapus"],
                    }
                  ),
                ],
              }
            ),
          ],
        }
      ),
  },
];
</script>

<template>
  <Head title="Kategori" />

  <DashboardLayout :breadcrumbs="breadcrumbs">
    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="mt-6 bg-card rounded-lg p-6 border border-border">
          <h1 class="text-2xl font-bold text-foreground">Manajemen Kategori</h1>
          <p class="text-muted-foreground mt-2">
            Halaman untuk mengelola data kategori berita
          </p>
        </div>

        <Card class="mt-6">
          <CardHeader>
            <CardTitle>Daftar Kategori</CardTitle>
          </CardHeader>
          <CardContent class="px-3 sm:px-6">
            <DataTable
              :columns="columns"
              :data="filteredData"
              :isLoading="isLoading"
              :enableBulkActions="false"
            >
              <template #toolbar-actions>
                <Dialog v-model:open="isDialogOpen">
                  <DialogTrigger as-child>
                    <Button variant="outline" @click="openDialog()" class="gap-2">
                      <Plus class="h-4 w-4" />
                      Tambah Kategori
                    </Button>
                  </DialogTrigger>
                  <DialogContent>
                    <DialogHeader>
                      <DialogTitle
                        >{{ editingItem ? "Edit" : "Tambah" }} Kategori</DialogTitle
                      >
                      <DialogDescription>
                        {{ editingItem ? "Perbarui" : "Buat" }} informasi kategori
                      </DialogDescription>
                    </DialogHeader>
                    <div class="space-y-4">
                      <div class="space-y-2">
                        <Label for="nama">Nama</Label>
                        <Input
                          id="nama"
                          v-model="formData.nama"
                          @input="formData.slug = generateSlug(formData.nama)"
                        />
                      </div>
                      <div class="space-y-2">
                        <Label for="slug">Slug</Label>
                        <Input id="slug" v-model="formData.slug" />
                      </div>
                      <div class="space-y-2">
                        <Label for="deskripsi">Deskripsi</Label>
                        <Textarea id="deskripsi" v-model="formData.deskripsi" rows="3" />
                      </div>
                      <div class="flex justify-end gap-2">
                        <Button variant="outline" @click="isDialogOpen = false"
                          >Batal</Button
                        >
                        <Button @click="saveItem">Simpan</Button>
                      </div>
                    </div>
                  </DialogContent>
                </Dialog>
              </template>
            </DataTable>
          </CardContent>
        </Card>
      </div>
    </div>
  </DashboardLayout>
</template>
