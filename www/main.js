/*

window.addEventListener("DOMContentLoaded",()=>{
    let createNote= document.querySelector("#create")
    let showForm= document.querySelector("#note-form")
    createNote.addEventListener("click",()=>{
        showForm.style.display="block";
    })

    // View mode switching
    const gridViewBtn = document.querySelector("#grid-view");
    const listViewBtn = document.querySelector("#list-view");
    const notesContainer = document.querySelector("#notes-container");

    console.log("Grid button:", gridViewBtn);
    console.log("List button:", listViewBtn);
    console.log("Notes container:", notesContainer);

    gridViewBtn.addEventListener("click", () => {
        console.log("Grid view clicked");
        notesContainer.classList.remove("list-view");
        gridViewBtn.classList.add("active");
        listViewBtn.classList.remove("active");
    });

    listViewBtn.addEventListener("click", () => {
        console.log("List view clicked");
        notesContainer.classList.add("list-view");
        listViewBtn.classList.add("active");
        gridViewBtn.classList.remove("active");
    });
});

*/

  // Dùng JavaScript (auto-save mỗi 300ms)
let timeout;
let lastSavedContent = ''; // Lưu lại nội dung cuối cùng đã lưu

document.getElementById('noteForm').addEventListener('input', () => {
  clearTimeout(timeout);

  timeout = setTimeout(() => {
    const form = document.getElementById('noteForm');
    const formData = new FormData(form);
    const currentContent = formData.get('content'); // Giả sử name="noteContent"

    // Chỉ lưu khi nội dung thay đổi
    if (currentContent !== lastSavedContent) {
      fetch('Note/save_note.php', { method: 'POST', body: formData })
        .then(() => {
          lastSavedContent = currentContent;
          location.reload(); // Cập nhật lại nội dung đã lưu
        })
        .catch(err => {
          console.error('Lỗi khi lưu note:', err);
        });
    }
  }, 300);
});
 



function toggleView(view) {
  const container = document.getElementById('notesContainer');
  container.className = view === 'grid' ? 'row row-cols-3' : 'list-group';
}