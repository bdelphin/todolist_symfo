const app = {
    init: function () {
        let tasks = document.querySelectorAll('.task__checkbox');

        for (const task of tasks) {
            task.addEventListener('change', app.handleTaskChecked);
        }
    },

    handleTaskChecked: async function (event) {
        const taskId = event.currentTarget.id.split('__')[1];

        try {
            const response = await fetch('/tasks/' + taskId + '/complete', {
                method: 'PATCH',
                body: JSON.stringify({
                    done: true
                }),
                headers: {
                    'Content-type': 'application/json'
                }
            });

            if (response.status === 200) {
                console.log("ok !");
            }

        } catch (error) {
            console.error("error updating task : " + error);
        }
    }
}

document.addEventListener('DOMContentLoaded', app.init);