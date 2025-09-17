<script setup lang="ts">
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
import { ColumnDef } from "@tanstack/vue-table";
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
import api from "@/lib/axios";

interface Kategori {
  id: number;
  nama: string;
  slug: string;
  deskripsi?: string;
  created_at?: Date;
  updated_at?: Date;
}

const breadcrumbs = [{ title: "Dashboard", href: "/dashboard" }, { title: "Kategori" }];

const data = ref<Kategori[]>([]);
const filteredData = ref<Kategori[]>([]);
const isLoading = ref(true);
const searchQuery = ref("");
const isDialogOpen = ref(false);
const editingItem = ref<Kategori | null>(null);

const formData = reactive({
  nama: "",
  slug: "",
  deskripsi: "",
});

// Fetch data from API
const fetchKategoriData = async () => {
  isLoading.value = true;
  try {
    const response = await api.get("/api/kategori");
    data.value = Array.isArray(response.data) ? response.data : response.data.data || [];
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

const generateSlug = (name: string) => {
  return name
    .toLowerCase()
    .replace(/[^\w ]+/g, "")
    .replace(/ +/g, "-");
};

const openDialog = (item: Kategori | null = null) => {
  editingItem.value = item;
  if (item) {
    Object.assign(formData, item);
  } else {
    Object.keys(formData).forEach((k) => (formData[k as keyof typeof formData] = ""));
  }
  isDialogOpen.value = true;
};

const saveItem = async () => {
  try {
    // Auto generate slug from name
    if (!formData.slug && formData.nama) {
      formData.slug = generateSlug(formData.nama);
    }

    if (editingItem.value) {
      await api.put(`/api/kategori/${editingItem.value.id}`, formData);
      toast.success("Kategori berhasil diperbarui");
    } else {
      await api.post("/api/kategori", formData);
      toast.success("Kategori berhasil ditambahkan");
    }

    await fetchKategoriData();
    applySearch();
    isDialogOpen.value = false;
  } catch (error: any) {
    const errorMessage = error.response?.data?.message || "Gagal menyimpan kategori";
    toast.error(errorMessage);
    console.error(error);
  }
};

const handleDelete = async (item: Kategori) => {
  if (!(await confirmModal("Apakah yakin ingin menghapus kategori ini?", item.nama)))
    return;

  const loadingToast = toast.loading("Menghapus kategori...");
  try {
    await api.delete(`/api/kategori/${item.id}`);
    toast.success("Kategori berhasil dihapus", { id: loadingToast });
    await fetchKategoriData();
    applySearch();
  } catch (error: any) {
    const errorMessage = error.response?.data?.message || "Gagal menghapus kategori";
    toast.error(errorMessage, { id: loadingToast });
    console.error(error);
  }
};

// Bulk action functions
const bulkDelete = async (selectedRows: Kategori[]) => {
  if (!selectedRows || selectedRows.length === 0) {
    toast.error("Tidak ada item yang dipilih");
    return;
  }

  const selectedNames = selectedRows.map(row => row.nama).filter(Boolean);
  const count = selectedRows.length;
  const confirmMessage = `Apakah yakin ingin menghapus ${count} kategori ini?`;

  const confirmed = await confirmModal(confirmMessage, selectedNames.join(", "));
  if (!confirmed) return;

  const loadingToast = toast.loading("Menghapus kategori...");

  try {
    const apiPromises = selectedRows.map(async (kategori) => {
      return api.delete(`/api/kategori/${kategori.id}`);
    });
    await Promise.all(apiPromises);

    await fetchKategoriData();
    applySearch();

    toast.success(`Kategori berhasil dihapus (${count} item)`, { id: loadingToast });
  } catch (err: any) {
    console.error("Gagal melakukan bulk delete:", err);
    const errorMessage = err.response?.data?.message || "Gagal melakukan bulk delete";
    toast.error(errorMessage, { id: loadingToast });
    throw err;
  }
};

onMounted(fetchKategoriData);

const columns: ColumnDef<Kategori>[] = [
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
      return h("div", {}, date ? new Date(date as string).toLocaleDateString("id-ID") : "-");
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
              :enableBulkActions="true"
            >
              <template #toolbar-actions>
                <Dialog v-model:open="isDialogOpen">
                  <DialogTrigger as-child>
                    <Button @click="openDialog()" class="gap-2">
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
              <template #bulk-actions="{ executeBulkAction }">
                <Button
                  variant="destructive"
                  @click="() => executeBulkAction(bulkDelete)"
                  class="w-full sm:w-auto"
                >
                  <Trash2 class="h-4 w-4" />
                  <span class="hidden sm:inline">Hapus Terpilih</span>
                  <span class="sm:hidden">Hapus</span>
                </Button>
              </template>
            </DataTable>
          </CardContent>
        </Card>
      </div>
    </div>
  </DashboardLayout>
</template>
