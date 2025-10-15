/**
 * Mengubah angka menjadi format mata uang Rupiah (Rp 10.000).
 * @param {number} amount - Angka yang akan diformat.
 * @returns {string} String dalam format Rupiah.
 */
export function formatRupiah(amount: number): string {
  return new Intl.NumberFormat("id-ID", {
    style: "currency",
    currency: "IDR",
    minimumFractionDigits: 0,
    maximumFractionDigits: 0,
  }).format(amount);
}


export function formatRupiahInput(value: string) {
    if (value == null || value === '') return '';
    const number = parseInt(value.toString().replace(/[^\d]/g, ''));
    if (isNaN(number)) return '';
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(number);
}

export function parseRupiahToNumber(value: string) {
    if (!value) return 0;
    return parseInt(value.toString().replace(/[^\d]/g, '')) || 0;
}

