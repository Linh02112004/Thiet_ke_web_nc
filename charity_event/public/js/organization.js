document.addEventListener('DOMContentLoaded', () => {
    const btnCreateEvent = document.getElementById('btnCreateEvent');
    const modalCreateEvent = document.getElementById('modalCreateEvent');
    const btnCancelCreate = document.getElementById('btnCancelCreate');

    btnCreateEvent.addEventListener('click', () => {
        modalCreateEvent.classList.remove('hidden');
    });

    btnCancelCreate.addEventListener('click', () => {
        modalCreateEvent.classList.add('hidden');
    });

    function openUpdateModal() {
        document.getElementById('updateInfoModal').style.display = 'block';
    }

    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }
});
