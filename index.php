<!DOCTYPE html>
<html>

<head>
  <title>Notebook App</title>
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <div class="container">
    <h1>Notebook App</h1>
    <div class="note-form">
      <h2>Add Note</h2>
      <form id="note-add">
        <div class="form-group">
          <label for="note-title" id="title-key">Title:</label>
          <input type="text" id="noteTitle" placeholder="Enter title" required>
        </div>
        <div class="form-group">
          <label for="note-description">Description:</label>
          <textarea id="noteContent" placeholder="Enter description" required></textarea>
        </div>
        <button type="submit" id="add-note">Add Note</button>
      </form>
    </div>
    <div class="note-list">
      <h2>Note List</h2>
      <ul id="noteList">



      </ul>
    </div>
  </div>

  <script src="js/script.js"></script>
</body>

</html>