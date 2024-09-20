document.addEventListener('DOMContentLoaded', () => {
    // Functions to open and close a modal
    function openModal($el, $name) {
      let $title = $name;
      let $name_input = document.querySelector("#open_free_name_input");

      if ($name.startsWith("category-") && document.querySelector('#' + $name) != null && document.querySelector('#' + $name).value != '') {
        $title = document.querySelector('#' + $name).value;
        $name_input.dataset.category = $name;
      } else {
        $name_input.dataset.category = "";
      }

      if (!$title.startsWith("category-")) {
        $el.classList.add('is-active');
        $el.querySelector('.modal-card-title').textContent = $title;
      }
    }
  
    function closeModal($el) {
      $el.classList.remove('is-active');
    }
  
    function closeAllModals() {
      (document.querySelectorAll('.modal') || []).forEach(($modal) => {
        closeModal($modal);
      });
    }

    function callAndReload($page, $link) {
      let xhr = new XMLHttpRequest();

        xhr.onreadystatechange = function() {
          if (xhr.readyState === 4) {
            if (xhr.status === 200) {
              location.reload();
            }
          }
        }

        xhr.open('GET', $page + '?' + $link, true);
        xhr.send();
    }
  
    // Add a click event on buttons to open a specific modal
    (document.querySelectorAll('.bring-it') || []).forEach(($trigger) => {
      const $name = $trigger.dataset.name;
      const $target = document.querySelector('.modal');
  
      $trigger.addEventListener('click', () => {
        openModal($target, $name);
      });
    });
  
    // Add a click event on various child elements to close the parent modal
    (document.querySelectorAll('.modal-background, .modal-close, .modal-card-head .delete, .modal-card-foot .button') || []).forEach(($close) => {
      const $target = $close.closest('.modal');
  
      $close.addEventListener('click', () => {
        closeModal($target);
      });
    });
  
    // Add a keyboard event to close all modals
    document.addEventListener('keydown', (event) => {
      if(event.key === "Escape") {
        closeAllModals();
      }
    });

    (document.querySelectorAll('.remove-brought') || []).forEach(($remove) => {
      const $link = $remove.dataset.link;
      $remove.addEventListener('click', () => {
        callAndReload('remove-brought.php', $link);
      });
    });

    (document.querySelectorAll('.remove-category') || []).forEach(($remove) => {
      const $link = $remove.dataset.link;
      $remove.addEventListener('click', () => {
        callAndReload('remove-category.php', $link);
      });
    });

    (document.querySelectorAll('.remove-new-tag') || []).forEach(($remove) => {
      const $link = $remove.dataset.link;
      $remove.addEventListener('click', () => {
        callAndReload('remove-new-tag.php', $link);
      });
    });

    (document.querySelectorAll('.remove-object') || []).forEach(($remove) => {
      const $link = $remove.dataset.link;
      $remove.addEventListener('click', () => {
        callAndReload('remove-object.php', $link);
      });
    });

    (document.querySelectorAll('.add-category') || []).forEach(($add) => {
      const $link = $add.dataset.link;
      $add.addEventListener('click', () => {
        const $category_key = document.getElementById("add-category-key").value;
        const $category_title = encodeURIComponent(document.getElementById("add-category-title").value);
        callAndReload('add-category.php', $link + '&key=' + $category_key + '&title=' + $category_title);
      });
    });

    (document.querySelectorAll('.add-open') || []).forEach(($add) => {
      const $category_id = $add.dataset.name;
      const $link = $add.dataset.link;
      $add.addEventListener('click', () => {
        const $obj_name = document.getElementById($category_id + "-object").value;
        const $obj_count = encodeURIComponent(document.getElementById($category_id + "-count").value);
        callAndReload('add-object.php', $link + '&object=' + $obj_name + '&count=' + $obj_count + '&category=' + $category_id.substring(9));
      });
    });

    (document.querySelectorAll('.category-target-decrement') || []).forEach(($remove) => {
      const $link = $remove.dataset.link;
      $remove.addEventListener('click', () => {
        callAndReload('category-target-decrement.php', $link);
      });
    });

    (document.querySelectorAll('.category-target-increment') || []).forEach(($remove) => {
      const $link = $remove.dataset.link;
      $remove.addEventListener('click', () => {
        callAndReload('category-target-increment.php', $link);
      });
    });

    document.querySelector('#open_free_name_input').addEventListener('input', (event) => {
      document.querySelector('#open_free_accept').disabled = document.querySelector('#open_free_name_input').value == '';
    });

    document.querySelector('#open_free_accept').addEventListener('click', () => {
      let xhr = new XMLHttpRequest();

      xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
          if (xhr.status === 200) {
            location.reload();
          }
        }
      }

      const object = encodeURIComponent(document.querySelector(".modal-card-title").textContent);
      const name = encodeURIComponent(document.querySelector("#open_free_name_input").value);
      const category = document.querySelector("#open_free_name_input").dataset.category;
      var url = 'ibringit.php?object=' + object + '&name=' + name;
      if (category != "") {
        url = url + '&category=' + encodeURIComponent(category.substring(9))
      }
      xhr.open('GET', url, true);
      xhr.send();
    });
});
