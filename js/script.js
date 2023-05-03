const noteTitle = document.getElementById("noteTitle");
const noteContent = document.getElementById("noteContent");
const noteList = document.getElementById("noteList");
const AddNote = document.getElementById("note-add");
const titleKey = document.getElementById("title-key");
// Add note function

AddNote.addEventListener("submit", (e) => {
  const title = noteTitle.value;
  const content = noteContent.value;

  if (!title) {
    alert("Please enter a title for your note.");
    return;
  }

  if (!titleKey.getAttribute("key")) {
    fetch("http://localhost:4000/notebook.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        title: title,
        description: content,
      }),
    })
      .then((response) => response.json())
      .then((note) => {
        console.log(note);
        // Add the new note to the list on the page
        const listItem = document.createElement("li");
        listItem.innerHTML = `
      <div class="note">
        <h2>${note.title}</h2>
        <p>${note.description}</p>
        <button class="delete-button" onclick="deleteNote(${note.id})">Delete</button>
        <button class="delete-button" onclick="updateNote(${note.id})">Update</button>
      </div>
    `;
        noteList.appendChild(listItem);

        // Clear the form fields
        noteTitle.value = "";
        noteContent.value = "";
      })
      .catch((error) => {
        console.error(error);
        alert("Unable to add note. Please try again later.");
      });
  } else {
    const id = titleKey.getAttribute("key");
    // Send a PUT request to the API to update the note in the database
    fetch(`http://localhost:4000/notebook.php?id=${id}`, {
      method: "PUT",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        id: id,
        title: noteTitle.value,
        description: noteContent.value,
      }),
    });
  }
  // Send a POST request to the API to add the note to the database
});

//Update Note function
function updateNote(id) {
  fetch(`http://localhost:4000/notebook.php?id=${id}`)
    .then((data) => data.json())
    .then((note) => {
      noteTitle.value = note.title;
      noteContent.value = note.description;
      titleKey.setAttribute("key", id.toString());
    })
    .catch((error) => {
      console.log(error);
      alert("Unable to update note. Please try again later.");
    });
}

// Delete note function
function deleteNote(id) {
  // Send a DELETE request to the API to delete the note from the database
  fetch(`http://localhost:4000/notebook.php?id=${id}`, {
    method: "DELETE",
  })
    .then((response) => {
      // Remove the deleted note from the list on the page
      const listItem = document.querySelector(`li[data-id="${id}"]`);
      noteList.removeChild(listItem);
    })
    .catch((error) => {
      console.error(error);
      alert("Unable to delete note. Please try again later.");
    });
}

// Load notes function
function loadNotes() {
  // Send a GET request to the API to retrieve all notes from the database
  fetch("http://localhost:4000/notebook.php")
    .then((response) => response.json())
    .then((notes) => {
      if (notes.data === undefined) {
        noteList.innerHTML = `<h3 style="text-align:center;color:#ff9966">
            There is no note currently available.
          </h3>`;
      } else {
        notes.data.map((note) => {
          const listItem = document.createElement("li");
          listItem.dataset.id = note.id;
          listItem.innerHTML = `
        <div class="note">
          <h2>${note.title}</h2>
          <p>${note.description}</p>
          <button class="delete-button" onclick="deleteNote(${note.id})">Delete</button>
          <button class="delete-button" onclick="updateNote(${note.id})">Update</button>
        </div>
      `;
          noteList.appendChild(listItem);
          titleKey.removeAttribute("key");
        });
      }
    })
    .catch((error) => {
      console.error(error);
      alert("Unable to load notes. Please try again later.");
    });
}

// Load notes when the page is loaded
loadNotes();
