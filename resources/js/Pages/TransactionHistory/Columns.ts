import { ColumnDef } from "@tanstack/vue-table";
import { h } from "vue";
import { Link } from "@inertiajs/vue3";
import dayjs from "dayjs";
import "dayjs/locale/id";
dayjs.locale("id");
import { Eye } from "lucide-vue-next";
import { formatRupiah } from "@/utils/formatRupiah";

export interface Transaction {
    transaction_code: string;
    total_amount: number;
    payment_method: string;
    status: string;
    created_at: string;
}

const formatDate = (date: string) => dayjs(date).format("DD MMMM YYYY, HH:mm");

export const columns: ColumnDef<Transaction>[] = [
    {
        accessorKey: "transaction_code",
        header: "Kode Transaksi",
        cell: (info) => info.getValue(),
    },
    {
        accessorKey: "payment_method",
        header: "Metode Pembayaran",
        cell: (info) => info.getValue(),
    },
    {
        accessorKey: "total_amount",
        header: "Total",
        cell: (info) => formatRupiah(info.getValue() as number),
    },
    {
        accessorKey: "status",
        header: "Status",
        cell: (info) => {
            const status = info.getValue() as string;
            const colorClass =
                status === "completed"
                    ? "bg-green-100 text-green-700"
                    : status === "pending"
                    ? "bg-yellow-100 text-yellow-700"
                    : "bg-red-100 text-red-700";

            return h(
                "span",
                {
                    class: `px-3 py-1 rounded-full text-xs font-semibold ${colorClass}`,
                },
                status
            );
        },
    },
    {
        accessorKey: "created_at",
        header: "Tanggal",
        cell: (info) => formatDate(info.getValue() as string),
    },
    {
        id: "actions",
        header: "Aksi",
        cell: (info) => {
            const trx = info.row.original;

            // 🧩 gunakan komponen Link inertia, bukan string HTML
            return h(
                Link,
                {
                    href: `/transactions/${trx.transaction_code}`,
                    class: "inline-block px-3 py-1 text-xs font-medium text-white rounded transition hover:opacity-50",
        style: "background-color: #23A948;",
                },
                { default: () => [h(Eye, { size: 14 })] }
            );
        },
    },
];
