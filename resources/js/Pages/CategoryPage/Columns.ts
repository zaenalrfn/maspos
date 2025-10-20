import { ColumnDef } from "@tanstack/vue-table";

export interface Category {
    id: number;
    name: string;
    products_count?: number;
    created_at?: string;
    updated_at?: string;
}

// Column definitions untuk TanStack Table
export const columns: ColumnDef<Category>[] = [
    {
        accessorKey: "id",
        header: "#",
        size: 60,
        enableSorting: false,
        cell: (info) => info.row.index + 1,
    },
    {
        accessorKey: "name",
        header: "Nama Kategori",
        enableSorting: true,
        cell: (info) => info.getValue(),
    },
    {
        accessorKey: "products_count",
        header: "Jumlah Produk",
        size: 200,
        enableSorting: true,
        cell: (info) => `${info.getValue() || 0} produk`,
    },
    {
        id: "actions",
        header: "Aksi",
        size: 200,
        enableSorting: false,
        cell: (info) => info.row.original,
    },
];
