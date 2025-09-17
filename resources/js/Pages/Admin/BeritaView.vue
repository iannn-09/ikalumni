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
import { Plus, MoreVertical, Pencil, Trash2, Eye } from "lucide-vue-next";
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
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/Components/ui/select";
import { toast } from "vue-sonner";
import { confirmModal } from "@/Components/ui/confirmation-dialog";
import { Head } from "@inertiajs/vue3";
import api from "@/lib/axios";

interface Kategori {
  id: number;
  nama: string;
  slug: string;
  deskripsi?: string;
}

interface User {
  id: number;
  name: string;
  email: string;
}

interface Berita {
  id: number;
  judul: string;
  slug: string;
  content: string;
  thumbnail?: string;
  kategori_id?: number;
  user_id: number;
  status: "draft" | "published" | "archived";
  published_at?: string;
  kategori?: Kategori;
  user?: User;
  created_at?: Date;
  updated_at?: Date;
}

const breadcrumbs = [{ title: "Dashboard", href: "/dashboard" }, { title: "Berita" }];

const data = ref<Berita[]>([]);
const filteredData = ref<Berita[]>([]);
const isLoading = ref(true);
const searchQuery = ref("");
const isDialogOpen = ref(false);
const editingItem = ref<Berita | null>(null);
const kategoriList = ref<Kategori[]>([]);

const formData = reactive({
  judul: "",
  slug: "",
  content: "",
  thumbnail: "",
  kategori_id: "",
  user_id: 1, // Default user ID, should be dynamic based on auth
  status: "draft" as const,
  published_at: "",
});

// Fetch data from API
const fetchBeritaData = async () => {
  isLoading.value = true;
  try {
    const response = await api.get("/api/berita");
    data.value = Array.isArray(response.data) ? response.data : response.data.data || [];
    filteredData.value = data.value;
  } catch (error) {
    toast.error("Gagal memuat data berita");
    console.error(error);
    data.value = [];
    filteredData.value = [];
  } finally {
    isLoading.value = false;
  }
};

const fetchKategoriData = async () => {
  try {
    const response = await api.get("/api/kategori");
    kategoriList.value = Array.isArray(response.data)
      ? response.data
      : response.data.data || [];
  } catch (error) {
    console.error("Gagal memuat kategori:", error);
  }
};

const applySearch = () => {
  if (!searchQuery.value.trim()) {
    filteredData.value = data.value;
    return;
  }
  filteredData.value = data.value.filter((item) =>
    item.judul.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
};

const generateSlug = (title: string) => {
  return title
    .toLowerCase()
    .replace(/[^\w ]+/g, "")
    .replace(/ +/g, "-");
};

const openDialog = (item: Berita | null = null) => {
  editingItem.value = item;
  if (item) {
    Object.assign(formData, {
      ...item,
      kategori_id: item.kategori_id?.toString() || "",
    });
  } else {
    Object.keys(formData).forEach((k) => {
      if (k === "status") formData[k] = "draft";
      else if (k === "user_id") formData[k] = 1;
      else formData[k as keyof typeof formData] = "";
    });
  }
  isDialogOpen.value = true;
};

const saveItem = async () => {
  try {
    // Auto generate slug from title
    if (!formData.slug && formData.judul) {
      formData.slug = generateSlug(formData.judul);
    }

    const payload = { ...formData };
    if (payload.kategori_id) {
      payload.kategori_id = parseInt(payload.kategori_id);
    }

    if (editingItem.value) {
      await api.put(`/api/berita/${editingItem.value.id}`, payload);
      toast.success("Berita berhasil diperbarui");
    } else {
      await api.post("/api/berita", payload);
      toast.success("Berita berhasil ditambahkan");
    }

    await fetchBeritaData();
    applySearch();
    isDialogOpen.value = false;
  } catch (error: any) {
    const errorMessage = error.response?.data?.message || "Gagal menyimpan berita";
    toast.error(errorMessage);
    console.error(error);
  }
};

const handleDelete = async (item: Berita) => {
  if (!(await confirmModal("Apakah yakin ingin menghapus berita ini?", item.judul)))
    return;

  const loadingToast = toast.loading("Menghapus berita...");
  try {
    await api.delete(`/api/berita/${item.id}`);
    toast.success("Berita berhasil dihapus", { id: loadingToast });
    await fetchBeritaData();
    applySearch();
  } catch (error: any) {
    const errorMessage = error.response?.data?.message || "Gagal menghapus berita";
    toast.error(errorMessage, { id: loadingToast });
    console.error(error);
  }
};

// Bulk action functions
const bulkDelete = async (selectedRows: Berita[]) => {
  if (!selectedRows || selectedRows.length === 0) {
    toast.error("Tidak ada item yang dipilih");
    return;
  }

  const selectedNames = selectedRows.map((row) => row.judul).filter(Boolean);
  const count = selectedRows.length;
  const confirmMessage = `Apakah yakin ingin menghapus ${count} berita ini?`;

  const confirmed = await confirmModal(confirmMessage, selectedNames.join(", "));
  if (!confirmed) return;

  const loadingToast = toast.loading("Menghapus berita...");

  try {
    const apiPromises = selectedRows.map(async (berita) => {
      return api.delete(`/api/berita/${berita.id}`);
    });
    await Promise.all(apiPromises);

    await fetchBeritaData();
    applySearch();

    toast.success(`Berita berhasil dihapus (${count} item)`, { id: loadingToast });
  } catch (err: any) {
    console.error("Gagal melakukan bulk delete:", err);
    const errorMessage = err.response?.data?.message || "Gagal melakukan bulk delete";
    toast.error(errorMessage, { id: loadingToast });
    throw err;
  }
};

onMounted(() => {
  fetchBeritaData();
  fetchKategoriData();
});

const getStatusColor = (status: string) => {
  switch (status) {
    case "published":
      return "bg-green-100 text-green-800";
    case "draft":
      return "bg-yellow-100 text-yellow-800";
    case "archived":
      return "bg-gray-100 text-gray-800";
    default:
      return "bg-gray-100 text-gray-800";
  }
};

const columns: ColumnDef<Berita>[] = [
  {
    id: "nomor",
    header: "No",
    cell: ({ row }) => h("div", {}, row.index + 1),
  },
  {
    accessorKey: "judul",
    header: ({ column }) => h(DataTableColumnHeader, { column, title: "Judul" }),
    cell: ({ row }) =>
      h("div", { class: "font-medium max-w-xs truncate" }, row.getValue("judul")),
  },
  {
    accessorKey: "kategori",
    header: ({ column }) => h(DataTableColumnHeader, { column, title: "Kategori" }),
    cell: ({ row }) => h("div", {}, row.original.kategori?.nama || "-"),
  },
  {
    accessorKey: "user",
    header: ({ column }) => h(DataTableColumnHeader, { column, title: "Penulis" }),
    cell: ({ row }) => h("div", {}, row.original.user?.name || "-"),
  },
  {
    accessorKey: "status",
    header: ({ column }) => h(DataTableColumnHeader, { column, title: "Status" }),
    cell: ({ row }) => {
      const status = row.getValue("status") as string;
      return h(
        "span",
        {
          class: `px-2 py-1 rounded text-xs font-semibold ${getStatusColor(status)}`,
        },
        status
      );
    },
  },
  {
    accessorKey: "published_at",
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: "Tanggal Publish" }),
    cell: ({ row }) => {
      const date = row.getValue("published_at");
      return h(
        "div",
        {},
        date ? new Date(date as string).toLocaleDateString("id-ID") : "-"
      );
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
  <Head title="Berita" />

  <DashboardLayout :breadcrumbs="breadcrumbs">
    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="mt-6 bg-card rounded-lg p-6 border border-border">
          <h1 class="text-2xl font-bold text-foreground">Manajemen Berita</h1>
          <p class="text-muted-foreground mt-2">Halaman untuk mengelola data berita</p>
        </div>

        <Card class="mt-6">
          <CardHeader>
            <CardTitle>Daftar Berita</CardTitle>
          </CardHeader>
          <CardContent class="px-3 sm:px-6" style="overflow-x: auto; width: 100%">
            <div style="min-width: 900px">
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
                        Tambah Berita
                      </Button>
                    </DialogTrigger>
                    <DialogContent class="max-w-2xl max-h-[90vh] overflow-y-auto">
                      <DialogHeader>
                        <DialogTitle
                          >{{ editingItem ? "Edit" : "Tambah" }} Berita</DialogTitle
                        >
                        <DialogDescription>
                          {{ editingItem ? "Perbarui" : "Buat" }} informasi berita
                        </DialogDescription>
                      </DialogHeader>
                      <div class="space-y-4">
                        <div class="space-y-2">
                          <Label for="judul">Judul</Label>
                          <Input
                            id="judul"
                            v-model="formData.judul"
                            @input="formData.slug = generateSlug(formData.judul)"
                          />
                        </div>
                        <div class="space-y-2">
                          <Label for="slug">Slug</Label>
                          <Input id="slug" v-model="formData.slug" />
                        </div>
                        <div class="space-y-2">
                          <Label for="kategori_id">Kategori</Label>
                          <Select v-model="formData.kategori_id">
                            <SelectTrigger>
                              <SelectValue placeholder="Pilih kategori" />
                            </SelectTrigger>
                            <SelectContent>
                              <SelectItem
                                v-for="kategori in kategoriList"
                                :key="kategori.id"
                                :value="kategori.id.toString()"
                              >
                                {{ kategori.nama }}
                              </SelectItem>
                            </SelectContent>
                          </Select>
                        </div>
                        <div class="space-y-2">
                          <Label for="content">Konten</Label>
                          <Textarea id="content" v-model="formData.content" rows="6" />
                        </div>
                        <div class="space-y-2">
                          <Label for="thumbnail">Thumbnail URL</Label>
                          <Input
                            id="thumbnail"
                            v-model="formData.thumbnail"
                            placeholder="https://..."
                          />
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                          <div class="space-y-2">
                            <Label for="status">Status</Label>
                            <Select v-model="formData.status">
                              <SelectTrigger>
                                <SelectValue />
                              </SelectTrigger>
                              <SelectContent>
                                <SelectItem value="draft">Draft</SelectItem>
                                <SelectItem value="published">Published</SelectItem>
                                <SelectItem value="archived">Archived</SelectItem>
                              </SelectContent>
                            </Select>
                          </div>
                          <div class="space-y-2">
                            <Label for="published_at">Tanggal Publish</Label>
                            <Input
                              id="published_at"
                              v-model="formData.published_at"
                              type="datetime-local"
                            />
                          </div>
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
            </div>
          </CardContent>
        </Card>
      </div>
    </div>
  </DashboardLayout>
</template>
