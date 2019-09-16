window.onload = function () {
	var doerListButton = document.querySelector('#doer-list-button');
	var customerListButton = document.querySelector('#customer-list-button');
	var doerList = document.querySelector('#doer-list');
	var customerList = document.querySelector('#customer-list');
	var deleteButtons = document.querySelectorAll('.delete-registration');

	doerListButton.onclick = function () {
		doerListButton.disabled = true;
		customerListButton.disabled = false;
		toggleVisibility(doerList, doerListButton);
		toggleVisibility(customerList, customerListButton);
	}

	customerListButton.onclick = function () {
		doerListButton.disabled = false;
		customerListButton.disabled = true;
		toggleVisibility(doerList, doerListButton);
		toggleVisibility(customerList, customerListButton);
	}

	addDeleting();

	function addDeleting() {
		for (button of deleteButtons) {
			button.onclick = function(e) {
				var id = e.target.dataset.reg_id;
				var type = e.target.dataset.reg_type;
				var xhr = new XMLHttpRequest();
				xhr.open('DELETE', `/admin.php?type=${type}&id=${id}`);
				xhr.send();
				xhr.onreadystatechange = function() {
					if(xhr.readyState == 4) {
						if(xhr.status == 204) {
							var item = e.target.closest('tr');
							var tbody = e.target.closest('tbody');
							tbody.removeChild(item);
						}
					}
				}
			}
		}
	}

	function toggleVisibility(elem, elemButton) {
		elem.classList.toggle('d-none');
		elem.classList.toggle('d-flex');
		elemButton.classList.toggle('disabled');
	}

};
