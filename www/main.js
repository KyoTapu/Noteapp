/*

window.addEventListener("DOMContentLoaded",()=>{
    let createNote= document.querySelector("#create")
    let showForm= document.querySelector("#note-form")
    createNote.addEventListener("click",()=>{
        showForm.style.display="block";
    })
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