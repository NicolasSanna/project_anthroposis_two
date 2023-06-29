class DeleteArticleManager
{
    constructor()
    {
        this.deleteForms = document.querySelectorAll('.Admin-Category-delete-Form')

        for (const deleteForm of this.deleteForms)
        {
            deleteForm.addEventListener('submit', this.onDeleteSubmit.bind(this))
        }
    }

    async onDeleteSubmit(e)
    {
        e.preventDefault()

        const confirmation = window.confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')

        if(confirmation === true)
        {
            const url = e.currentTarget.action

            const formData = new FormData(e.currentTarget);

            const options = {

                method: 'post',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body:formData
            };

            const response = await fetch(url, options)
            const categoryId = await response.json();

            const row = document.getElementById(`Admin-Category-${categoryId}`)

            if (row != null)
            {
                row.remove();
            }
        }
    }
}

const delArtManag = new DeleteArticleManager();