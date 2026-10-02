document.addEventListener('DOMContentLoaded', () => {

    const form = document.getElementById('admin-film-form');

    if(!form) {
        return;
    }

    form.addEventListener('submit', async (event) => {

        event.preventDefault();

        const form_data = new FormData(form);

        try {
            //initially creating the film
            const response = await fetch(form.action, {
                method: 'POST',
                body: form_data
            });

            const result = await response.json();

            //checking to see if the film was actually created
            if(!response.ok || result.success !== true) {
                alert(result.error || 'there was a problem creating the film');
            
            return;
            }

            //given the film was created successfully, xAPI statement is now sent
            const xapi_data = new FormData();

            xapi_data.append('film_id', result.film_id);

            const xapi_response = await fetch(
                "admin/send-film-xapi",
                {
                    method: 'POST',
                    body: xapi_data
                }
            );

            const xapi_result = await xapi_response.json();

            //check if the statement was actually sent
            if(!xapi_response.ok || xapi_result.success !== true) {

                console.error('xAPI response:', xapi_result);

                alert(
                    xapi_result.error || "the film was created but couldn't send xAPI statement"
                );

                return;
            }

            //everything succeeded
            window.location.href = "/admin-panel";
        } catch (error) {
            console.error('error creating film:', error);

            alert('there was a problem communicating with the server')
        }
    });
});