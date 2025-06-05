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
});
