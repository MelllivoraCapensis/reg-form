$(document).ready(function(){
	//sign-up tabs
	if($('.list-tabs').length) {
		$('.list-tabs a').click(function (e) {
			e.preventDefault();
			$(this).tab('show');
			$('.list-tabs a').parent("li").removeClass("active");
			$(this).parent("li").addClass("active");
		})
	}

	//Категории рубрик
	function formatState (state) {
		var parentHeadings = ["Родительская категория1", "Родительская категория2"]; //массив категорий для вывода в селект

		if (!state.id) {
			return state.text;
		}
		var $state = $(
			'<span>' + state.text + ' <span class="parents">/ ' + parentHeadings[0] + ',&nbsp;' + parentHeadings[1] + '</span>' + '</span>'
			);
		return $state;
	};
	
	//select2
	if($(".select2").length) {
		$(".select2").select2({
			language: "ru",
			placeholder: "Выберите рубрику",
			closeOnSelect: false,
			multiple: true,
			templateResult: formatState,
		});
	}
});

$(document).ready(function() {



	$("#doer-tel").mask("(999) 999-9999");
	$("#customer-tel").mask("(999) 999-9999");

	addUserFormScript('doer');
	addUserFormScript('customer');

	function addUserFormScript(type) {
		var cityWrapper = document.querySelector(
		`#${type}-city-wrapper`);
		var cityInput = document.querySelector(
			`#${type}-city-input`);
		var cityList = document.querySelector(
			`#${type}-city-list`);
		var rubricWrapper = document.querySelector(
			`#${type}-rubric-wrapper`);
		var rubricInput = document.querySelector(
			`#${type}-rubric-input`);
		var rubricList = document.querySelector(
			`#${type}-rubric-list`);
		var rubricChoice = document.querySelector(
			`#${type}-rubric-choice`);

		addResetErrors();

		$(`#${type}-tab form`).on('submit', function(e) {
			registerUser(e, type);
		});

		makeLiveSearch(cityInput, cityList, 
			cityWrapper, 'city');
		makeLiveSearch(rubricInput, rubricList,
			rubricWrapper, 'rubric', rubricChoice);
	}

	function registerUser (e, type) {
		var rubricChoice = document.querySelector(
			`#${type}-rubric-choice`);

		e.preventDefault();

		if(! validateEmail($(`#${type}-email`).val())) {
			$(`#${type}-email + .form-validator`).html('Некорректный email');
			$(`#${type}-email`).addClass('error');
		}
		else if(! getChosenRubrics(rubricChoice)) {
			$(`#${type}-rubric-wrapper .form-validator`).html('Некорректная рубрика');
			$(`#${type}-rubric-input`).addClass('error');
		}
		else {
			var xhr = new XMLHttpRequest();
			var form = document.querySelector(`#${type}-tab form`);
			var data = new FormData(form);
			data.append(`${type}-rubric`, 
				getChosenRubrics(rubricChoice) ? 
				getChosenRubrics(rubricChoice) : '');
			xhr.open('POST', '');
			xhr.send(data);

			xhr.onreadystatechange = function() {
				if(xhr.readyState == 4) {
					if(xhr.status == 201) {
						$("#modal-message").modal("show");
						form.reset();
						$(".form-choice").html('');
					}
					else {
						var response = JSON.parse(xhr.responseText);
						var fieldsForValidation = [`${type}-city`, `${type}-rubric`];
						fieldsForValidation.forEach(function(field) {
							if(response.hasOwnProperty(field)) {
								$(`#${field}-input`).addClass('error');
								$(`#${field}-input ~ .form-validator`).html(
									response[field]);
							}
						});
						
					}
				}
			}
		}
	}

	
	function makeLiveSearch(input, list, wrapper, type, choiceBox = null) {
		var START_MATCH_LEN = 3;

		input.onfocus = update;

		input.onkeyup = update;

		window.addEventListener('click', function(e) {
			list.addEventListener('click', function(e) {
				e.stopPropagation();
			});
			if(e.target != input) {
				list.classList.add('d-none');
			}
			
		})

		list.addEventListener('click', function(e) {
			var title = e.target.innerHTML;
			if(type == 'city') {
				input.value = title;
				list.classList.add('d-none');
			}
			else if(type == 'rubric') {
				if(titleInBox(title, choiceBox)) return;
				choiceBox.appendChild(createChoiceItem(
					title));

			}
			
		});
		

		function update() {
			if(input.value.length >= START_MATCH_LEN) {
				list.classList.remove('d-none');
				insertOptions(type, input, list);
			}
			else {
				list.classList.add('d-none');
			}
		}

		function createChoiceItem(text) {
			var item = document.createElement('li');
			item.classList.add('form-choice-item');

			var itemText = document.createElement('span');
			itemText.classList.add('form-choice-text');
			itemText.innerHTML = text;

			var itemCloser = document.createElement('span');
			itemCloser.classList.add('form-choice-close');
			itemCloser.innerHTML = '&#10006';

			itemCloser.onclick = function(e) {
				item.closest('.form-choice')
				.removeChild(item);
			};

			item.appendChild(itemText);
			item.appendChild(itemCloser);

			return item;
		}
		
	}

	function titleInBox(title, choiceBox) {
		var items = choiceBox.querySelectorAll(
			".form-choice-text");
		for (item of items) {
			if(item.innerHTML == title) return true;
		}
		return false;
	}

	function validateEmail(email) {
		var re = /\S+@\S+\.\S+/;
		return re.test(email);
	}

	function insertOptions(type, input, list) {
		var SELECT_SIZE = 5;
		var xhr = new XMLHttpRequest();
		xhr.open('GET', `/api.php?type=${type}&startswith=${input.value}`);
		xhr.send();
		xhr.onreadystatechange = function() {
			if(xhr.readyState == 4) {
				var data = JSON.parse(xhr.response);
				list.innerHTML = '';
				data.forEach( function(element, index) {
					var option = document.createElement('li');
					option.innerHTML = element;
					list.appendChild(option);
				});
				list.size = Math.min(data.length, SELECT_SIZE);
			}
		}
	}

	function getChosenRubrics(choiceBox) {
		var items = choiceBox.querySelectorAll(
			'.form-choice-text');
		if(items.length == 0) return false;
		var arr=[];
		for (item of items) {
			arr.push(item.innerHTML);
		}
		return arr.join('***');
	}

	function addResetErrors() {
		var inputs = document.querySelectorAll(
			'.form-control');

		for (input of inputs) {
			input.addEventListener('click', function(e) {
				var validationElem = e.target.closest(
					'.form-group').querySelector('.form-validator');
				e.target.classList.remove('error');
				validationElem.innerHTML = '';
			})
		}

	}

});

