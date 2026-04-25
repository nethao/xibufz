(function () {
	'use strict';

	function togglePanel(buttonSelector, panelSelector) {
		var button = document.querySelector(buttonSelector);
		var panel = document.querySelector(panelSelector);

		if (!button || !panel) {
			return;
		}

		button.addEventListener('click', function () {
			var isOpen = panel.classList.toggle('is-open');
			button.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
		});
	}

	document.addEventListener('DOMContentLoaded', function () {
		togglePanel('.mobile-search-toggle', '#mobile-search-panel');
		togglePanel('.mobile-nav-toggle', '#mobile-menu-panel');
	});
}());
