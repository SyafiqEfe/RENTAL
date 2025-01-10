document.addEventListener('DOMContentLoaded', function() {
    const carSelect = document.getElementById('car_id');
    const totalPriceInput = document.getElementById('total_price');
    const rentalDateInput = document.querySelector('input[name="rental_date"]');
    const returnDateInput = document.querySelector('input[name="return_date"]');

    function calculateTotalPrice() {
        const selectedOption = carSelect.options[carSelect.selectedIndex];
        const pricePerDay = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        const rentalDate = new Date(rentalDateInput.value);
        const returnDate = new Date(returnDateInput.value);

        if (rentalDate && returnDate && rentalDate < returnDate) {
            const timeDiff = returnDate - rentalDate;
            const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24)); // Menghitung jumlah hari
            const totalPrice = daysDiff * pricePerDay;
            totalPriceInput.value = 'Rp ' + totalPrice.toFixed(2);
        } else {
            totalPriceInput.value = '';
        }
    }

    carSelect.addEventListener('change', calculateTotalPrice);
    rentalDateInput.addEventListener('change', calculateTotalPrice);
    returnDateInput.addEventListener('change', calculateTotalPrice);
});

function calculateRentalDays() {
    const rentalDateInput = document.querySelector('input[name="rental_date"]');
    const returnDateInput = document.querySelector('input[name="return_date"]');
    const rentalDaysInput = document.querySelector('input[name="rental_days"]');

    const rentalDate = new Date(rentalDateInput.value);
    const returnDate = new Date(returnDateInput.value);

    // Pastikan kedua tanggal valid
    if (!isNaN(rentalDate) && !isNaN(returnDate)) {
        const timeDifference = returnDate - rentalDate;
        const dayDifference = Math.ceil(timeDifference / (1000 * 3600 * 24)); // Menghitung selisih hari

        if (dayDifference >= 0) {
            rentalDaysInput.value = dayDifference; // Menampilkan jumlah hari sewa
        } else {
            rentalDaysInput.value = ''; // Kosongkan jika tanggal kembali lebih awal
        }
    } else {
        rentalDaysInput.value = ''; // Kosongkan jika salah satu tanggal tidak valid
    }
}