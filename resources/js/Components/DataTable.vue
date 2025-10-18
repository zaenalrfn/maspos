<script setup lang="ts">
import {
    useVueTable,
    getCoreRowModel,
    getFilteredRowModel,
    getPaginationRowModel,
    getSortedRowModel,
    FlexRender,
} from "@tanstack/vue-table";
import { ref, watch } from "vue";
import type { SortingState } from "@tanstack/vue-table";
import { ArrowRight, ArrowLeft } from "lucide-vue-next";

interface Props<T> {
    data: T[];
    columns: any[];
    globalFilter?: string;
}

const props = defineProps<Props<any>>();
const emits = defineEmits(["edit", "deleted", "page-change"]);

const sorting = ref<SortingState>([]);
const pagination = ref({ pageIndex: 0, pageSize: 10 });
const globalFilter = ref(props.globalFilter ?? "");

// ⚡ Watch perubahan dari parent (realtime search)
watch(
    () => props.globalFilter,
    (val) => {
        globalFilter.value = val ?? "";
        table.setGlobalFilter(globalFilter.value);
    }
);

// 📋 Table setup
const table = useVueTable({
    get data() {
        return props.data ?? [];
    },
    columns: props.columns,
    state: {
        get globalFilter() {
            return globalFilter.value;
        },
        get sorting() {
            return sorting.value;
        },
        get pagination() {
            return pagination.value;
        },
    },
    onGlobalFilterChange: (updater) => {
        globalFilter.value =
            typeof updater === "function"
                ? updater(globalFilter.value)
                : updater;
    },
    onSortingChange: (updater) => {
        sorting.value =
            typeof updater === "function" ? updater(sorting.value) : updater;
    },
    onPaginationChange: (updater) => {
        pagination.value =
            typeof updater === "function" ? updater(pagination.value) : updater;
        emits("page-change", pagination.value);
    },
    getCoreRowModel: getCoreRowModel(),
    getFilteredRowModel: getFilteredRowModel(),
    getSortedRowModel: getSortedRowModel(),
    getPaginationRowModel: getPaginationRowModel(),
});
</script>

<template>
    <div
        class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl"
    >
        <!-- Desktop Table -->
        <div class="hidden overflow-x-auto md:block">
            <table class="w-full">
                <thead class="border-b border-gray-200 bg-gray-50">
                    <tr
                        v-for="headerGroup in table.getHeaderGroups()"
                        :key="headerGroup.id"
                    >
                        <th
                            v-for="header in headerGroup.headers"
                            :key="header.id"
                            @click="
                                header.column.getToggleSortingHandler()?.(
                                    $event
                                )
                            "
                            :class="[
                                'px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider',
                                header.column.getCanSort()
                                    ? 'cursor-pointer hover:bg-gray-100'
                                    : '',
                            ]"
                        >
                            <div class="flex items-center gap-2">
                                <FlexRender
                                    :render="header.column.columnDef.header"
                                    :props="header.getContext()"
                                />
                                <span
                                    v-if="header.column.getIsSorted()"
                                    class="text-blue-600"
                                >
                                    {{
                                        header.column.getIsSorted() === "asc"
                                            ? "↑"
                                            : "↓"
                                    }}
                                </span>
                            </div>
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    <tr
                        v-for="row in table.getRowModel().rows"
                        :key="row.id"
                        class="transition-colors hover:bg-gray-50"
                    >
                        <td
                            v-for="cell in row.getVisibleCells()"
                            :key="cell.id"
                            class="px-6 py-4 text-sm text-gray-700"
                        >
                            <template v-if="cell.column.id === 'actions'">
                                <div class="flex items-center gap-2">
                                    <button
                                        @click="emits('edit', row.original)"
                                        class="px-3 py-1.5 border border-gray-300 rounded-md text-xs font-medium text-gray-700 hover:bg-gray-50 hover:border-blue-500 hover:text-blue-600 transition-colors"
                                    >
                                        Edit
                                    </button>
                                    <button
                                        @click="emits('deleted', row.original)"
                                        class="px-3 py-1.5 border border-gray-300 rounded-md text-xs font-medium text-red-600 hover:bg-red-50 hover:border-red-500 transition-colors"
                                    >
                                        Hapus
                                    </button>
                                </div>
                            </template>
                            <template v-else>
                                <FlexRender
                                    :render="cell.column.columnDef.cell"
                                    :props="cell.getContext()"
                                />
                            </template>
                        </td>
                    </tr>

                    <!-- Empty State -->
                    <tr v-if="table.getRowModel().rows.length === 0">
                        <td
                            colspan="100%"
                            class="py-10 text-sm text-center text-gray-500"
                        >
                            Tidak ada data ditemukan.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div
            class="flex items-center justify-between p-4 mt-4 border-t bg-gray-50"
        >
            <div>
                <button
                    @click="table.previousPage()"
                    :disabled="!table.getCanPreviousPage()"
                    class="px-3 py-1 text-white bg-blue-600 border rounded disabled:opacity-50"
                >
                    <ArrowLeft />
                </button>
                <button
                    @click="table.nextPage()"
                    :disabled="!table.getCanNextPage()"
                    class="px-3 py-1 ml-2 text-white bg-blue-600 border rounded disabled:opacity-50"
                >
                    <ArrowRight />
                </button>
            </div>

            <span class="text-sm text-gray-600">
                Halaman {{ table.getState().pagination.pageIndex + 1 }} dari
                {{ table.getPageCount() }}
            </span>
        </div>
    </div>
</template>
