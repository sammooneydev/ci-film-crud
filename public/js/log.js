document.addEventListener('DOMContentLoaded', () => {

    const form = document.getElementById('film-log-form');

    if (!form) {
        return;
    }

    form.addEventListener('submit', async (event) => {

        event.preventDefault();

        const form_data = new FormData(form);

        try {

            const response = await fetch(form.action, {
                method: 'POST',
                body: form_data
            });

            const result = await response.json();

            if (!response.ok || result.success !== true) {
                alert(
                    result.error ||
                    'There was a problem logging the film.'
                );
                return;
            }

            window.location.href = '/profile';

        } catch (error) {

            console.error('Error logging film:', error);

            alert('There was a problem logging the film.');
        }
    });

});
