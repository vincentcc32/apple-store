document.addEventListener("DOMContentLoaded", () => {
  // menu
  const btnOpen = document.querySelector(".icon__bars");
  const btnClose = document.querySelector(".closeMenu");
  const mobileMenu = document.querySelector("header > div.fixed");

  btnOpen.addEventListener("click", () => {
    mobileMenu.classList.remove("-translate-x-full");
    mobileMenu.classList.add("translate-x-0");
  });

  btnClose.addEventListener("click", () => {
    mobileMenu.classList.remove("translate-x-0");
    mobileMenu.classList.add("-translate-x-full");
  });
  // search
  const searchModal = document.getElementById("searchModal");
  const searchBtn = document.querySelector(".icon__search");
  const closeSearch = document.getElementById("closeSearch");
  const overlaySearch = document.getElementById("overlaySearch");

  function openSearch() {
    searchModal.classList.remove("hidden");
    searchBtn.classList.add("hidden");
    requestAnimationFrame(() => {
      searchModal.classList.remove("opacity-0", "translate-y-4");
      searchModal.classList.add("opacity-100", "translate-y-0");
    });



  }

  function closeSearchModal() {
    searchBtn.classList.remove("hidden");
    searchModal.classList.add("opacity-0", "translate-y-4");
    searchModal.classList.remove("opacity-100", "translate-y-0");
    setTimeout(() => searchModal.classList.add("hidden"), 300); // đợi animation kết thúc
  }

  searchBtn.addEventListener("click", openSearch);
  closeSearch.addEventListener("click", closeSearchModal);
  overlaySearch.addEventListener("click", closeSearchModal);


});


function showToast() {
  const toast = document.getElementById('toast');
  if (toast) {
    toast.classList.remove('opacity-0');
    toast.classList.add('opacity-100');

    // Ẩn toast sau 3 giây
    setTimeout(() => {
      toast.classList.remove('opacity-100');
      toast.classList.add('opacity-0');
    }, 3000);
  }
}

// Mở Toast khi tải trang (hoặc bạn có thể gọi showToast() từ nơi khác)
window.onload = showToast;