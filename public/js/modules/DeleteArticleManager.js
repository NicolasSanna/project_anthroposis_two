class DeleteArticleManager
{
    constructor()
    {
        this.deleteForms = document.querySelectorAll('.Admin-Article-delete-Form');
        this.modal = document.querySelector('.Modal');
        this.confirmBtn = document.getElementById('confirm');
        this.cancelBtn = document.getElementById('cancel');
        this.currentDeleteForm = '';

        this.installEventHandlers();
    }

    installEventHandlers()
    {       
        this.confirmBtn.addEventListener('click', this.onClickConfirmBtn.bind(this));
        this.cancelBtn.addEventListener('click', this.onClickCancelBtn.bind(this));

        for (const deleteForm of this.deleteForms)
        {
            deleteForm.addEventListener('submit', this.onDeleteSubmit.bind(this));
        }
    }

    onDeleteSubmit(e)
    {
        e.preventDefault();

        this.modal.style.display = 'block';

        this.currentDeleteForm = e.currentTarget;
    }

    async onClickConfirmBtn()
    {
        const url = this.currentDeleteForm.action;

        const formData = new FormData(this.currentDeleteForm);

        const options = {

            method: 'post',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body:formData

        };

        const response = await fetch(url, options);
        const articleId = await response.json();

        const row = document.getElementById(`Admin-Article-${articleId}`);

        if (row != null)
        {
            row.remove();
        }

        this.modal.style.display = 'none';
    }

    onClickCancelBtn()
    {
        this.modal.style.display = 'none';
    }
}

const delArtManag = new DeleteArticleManager();