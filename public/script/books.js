import { fetchAuthorBooks, sendData, getQueryParameter } from './ajax.js'

document.addEventListener('DOMContentLoaded', () => {
    const appContainer = document.getElementById('app-container')

    const container = document.createElement('div')
    container.className = 'container'
    appContainer.appendChild(container)

    const addButtonContainer = document.createElement('div')
    addButtonContainer.className = 'add-container'

    const addButton = document.createElement('a')
    addButton.className = 'add'
    addButton.href = '#'

    const addImg = document.createElement('img')
    addImg.src = '../images/add.png'
    addImg.alt = 'add'
    addImg.className = 'add-image'

    addButton.appendChild(addImg)
    addButtonContainer.appendChild(addButton)
    appContainer.appendChild(addButtonContainer)

    const authorId = getQueryParameter('author_id')

    function loadBooks() {
        fetchAuthorBooks(authorId)
            .then(books => renderBooks(books))
            .catch(err => console.error('Error loading books:', error))
    }

    function renderBooks(books) {
        container.innerHTML = ''

        if (books.length === 0) {
            container.textContent = 'No books found for this author.'

            return
        }

        const table = document.createElement('table')

        const thead = document.createElement('thead')
        thead.innerHTML = `
            <tr>
                <th class="book-col">Book</th>
                <th class="actions-col">Actions</th>
            </tr>
        `
        table.appendChild(thead);

        const tbody = document.createElement('tbody')

        books.forEach(book => {
            const tr = document.createElement('tr')

            const tdTitle = document.createElement('td')
            tdTitle.innerHTML = `<b>${book.title} (${book.year})</b>`

            const tdActions = document.createElement('td')
            tdActions.className = 'actions-col'

            const actionDiv = document.createElement('div')
            actionDiv.className = 'actions'

            const editButton = document.createElement('a')
            editButton.className = 'button edit'
            editButton.href = '#'

            const editImg = document.createElement('img')
            editImg.src = '../images/pen.png'
            editImg.alt = 'pen'

            editButton.appendChild(editImg)
            editButton.onclick = () => showEditForm(book)

            const deleteButton = document.createElement('a')
            deleteButton.className = 'button delete'
            deleteButton.href = '#'

            const deleteImg = document.createElement('img')
            deleteImg.src = '../images/minus.png'
            deleteImg.alt = 'minus'

            deleteButton.appendChild(deleteImg)
            deleteButton.onclick = () => showDeleteDialog(book)

            actionDiv.appendChild(editButton)
            actionDiv.appendChild(deleteButton)
            tdActions.appendChild(actionDiv)

            tr.appendChild(tdTitle)
            tr.appendChild(tdActions)
            tbody.appendChild(tr)
        })

        table.appendChild(tbody)
        container.appendChild(table)
    }

    function showEditForm(book) {
        removeExistingForm();

        const formWrapper = document.createElement('div');
        formWrapper.className = 'form-wrapper';

        formWrapper.innerHTML = `
            <button type="button" class="close-form">X</button>

            <form>
                <fieldset>
                    <legend>Book Edit (${book.id})</legend>

                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" value="${book.title}">

                    <label for="year">Year</label>
                    <input type="text" id="year" name="year" value="${book.year}">

                    <div class="button-div">
                        <button type="submit">Save</button>
                    </div>
                </fieldset>
            </form>
        `

        formWrapper.querySelector('.close-form').addEventListener('click', () => {
            document.body.classList.remove('overlay-active');
            formWrapper.remove();
        });

        formWrapper.querySelector('form').onsubmit = (e) => {
            e.preventDefault();

            const updatedBook = {
                id: book.id,
                author_id: authorId,
                title: formWrapper.querySelector('#title').value,
                year: formWrapper.querySelector('#year').value
            };

            sendData(`api/books/${book.id}/edit`, 'POST', updatedBook)
                .then(() => {
                    document.body.classList.remove('overlay-active')
                    formWrapper.remove()
                    loadBooks()
                })
                .catch(error => console.error('Error updating book:', error));
        };

        document.body.classList.add('overlay-active')
        appContainer.appendChild(formWrapper);
    }

    function showAddForm() {
        removeExistingForm()

        const formWrapper = document.createElement('div')
        formWrapper.className = 'form-wrapper'

        formWrapper.innerHTML = `
            <button type="button" class="close-form">X</button>

            <form>
                <fieldset>
                    <legend>Add Book</legend>

                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" value="">

                    <label for="year">Year</label>
                    <input type="text" id="year" name="year" value="">

                    <div class="button-div">
                        <button type="submit">Save</button>
                    </div>

                </fieldset>
            </form>
        `

        formWrapper.querySelector('.close-form').addEventListener('click', () => {
            document.body.classList.remove('overlay-active');
            formWrapper.remove();
        });

        formWrapper.querySelector('form').onsubmit = (e) => {
            e.preventDefault()

            const newBook = {
                author_id: authorId,
                title: formWrapper.querySelector('#title').value,
                year: formWrapper.querySelector('#year').value
            }

            sendData(`api/books/create`, 'POST', newBook)
                .then(() => {
                    document.body.classList.remove('overlay-active')
                    formWrapper.remove()
                    loadBooks()
                })
                .catch(err => console.error('Error creating book:', error))
        }

        document.body.classList.add('overlay-active')
        appContainer.appendChild(formWrapper)
    }

    function showDeleteDialog(book) {
        removeExistingModal();

        const dialog = document.createElement('div');
        dialog.className = 'modal';
        dialog.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <span class="exclamation-icon">❗</span>
                    <h2>Delete Book</h2>
                </div>
                <div class="modal-body">
                    <p>You are about to delete the book ${book.title}. If you proceed with this action, the application will permanently delete this book from the database.</p>
                </div>
                <form>
                    <fieldset class="modal-footer">
                        <button id="cancelButton" name="action" value="cancel" type="button">Cancel</button>
                        <button id="deleteButton" name="action" value="delete" type="submit">Delete</button>
                    </fieldset>
                </form>
            </div>
        `;

        dialog.querySelector('#cancelButton').onclick = () => dialog.remove();
        dialog.querySelector('form').onsubmit = (e) => {
            e.preventDefault();

            sendData(`api/books/${book.id}/delete`, 'POST')
                .then(() => {
                    document.body.classList.remove('overlay-active')
                    dialog.remove();
                    loadBooks();
                })
                .catch(error => console.error('Error deleting book:', error));
        };

        document.body.classList.add('overlay-active')
        appContainer.appendChild(dialog);
    }

    function removeExistingForm() {
        const existingForm = document.querySelector('.form-wrapper')
        if (existingForm) existingForm.remove()
    }

    function removeExistingModal() {
        const existingModal = document.querySelector('.modal')
        if (existingModal) existingModal.remove()
    }

    addButton.onclick = (e) => {
        e.preventDefault()
        showAddForm()
    }

    loadBooks()
})