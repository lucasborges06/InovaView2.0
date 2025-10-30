// interactions: modal preview and small helpers
document.addEventListener('DOMContentLoaded', function(){
  // modal
  var modal = document.getElementById('modal');
  var modalImg = document.getElementById('modalImg');
  var modalTitle = document.getElementById('modalTitle');
  var modalDesc = document.getElementById('modalDesc');
  var closeBtn = document.getElementById('closeModal');
  document.querySelectorAll('.card').forEach(function(card){
    card.addEventListener('click', function(){
      var fn = card.getAttribute('data-filename');
      var t = card.getAttribute('data-title');
      var d = card.getAttribute('data-desc');
      modalImg.src = 'uploads/' + fn;
      modalTitle.textContent = t || '';
      modalDesc.textContent = d || '';
      modal.setAttribute('aria-hidden','false');
    });
  });
  closeBtn && closeBtn.addEventListener('click', function(){ modal.setAttribute('aria-hidden','true'); });
  modal.addEventListener('click', function(e){ if (e.target === modal) modal.setAttribute('aria-hidden','true'); });

  // prevent empty anchor default behavior
  document.addEventListener('click', function(e){
    if (e.target.matches('.btn') && e.target.getAttribute('href') === '#') e.preventDefault();
  }, false);
});
