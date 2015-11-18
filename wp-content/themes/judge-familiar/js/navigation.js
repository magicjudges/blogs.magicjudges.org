/**
 * navigation.js
 *
 * Handles toggling the navigation menu for small screens and enables tab
 * support for dropdown menus.
 */
( function() {
	var container, button, menu, links, subMenus;

	container = document.getElementById( 'site-navigation' );
	if ( ! container ) {
		return;
	}

	button = container.getElementsByTagName( 'button' )[0];
	if ( 'undefined' === typeof button ) {
		return;
	}

	menu = container.getElementsByTagName( 'ul' )[0];

	// Hide menu toggle button if menu is empty and return early.
	if ( 'undefined' === typeof menu ) {
		button.style.display = 'none';
		return;
	}

	menu.setAttribute( 'aria-expanded', 'false' );
	if ( -1 === menu.className.indexOf( 'nav-menu' ) ) {
		menu.className += ' nav-menu';
	}

	button.onclick = function() {
		if ( -1 !== container.className.indexOf( 'toggled' ) ) {
			container.className = container.className.replace( ' toggled', '' );
			button.setAttribute( 'aria-expanded', 'false' );
			menu.setAttribute( 'aria-expanded', 'false' );
		} else {
			container.className += ' toggled';
			button.setAttribute( 'aria-expanded', 'true' );
			menu.setAttribute( 'aria-expanded', 'true' );
		}
		this.blur();
	};

	var language = document.getElementById("secondary-menu");
	if ( ! language ) {
		return;
	}

	var languages = language.querySelectorAll("#secondary-menu > li");

	if ( languages.length === 0) {
		return;
	}

	var current = "en";
	if ( window.location.search ) {
		var re = /lang=([^&]+)/;
		var match = re.exec(window.location.search);
		if ( match[1] ) {
			current = match[1];
		}
	}

	for( var i = 0; i < languages.length; i++ ) {
		if ( languages[i].classList.contains("lang-" + current) ) {
			var submenu = document.createElement("ul");

			for ( var j = 0; j < languages.length; j++ ) {
				if ( i !== j ) {
					var newItem = languages[j].cloneNode(true);
					submenu.appendChild(newItem);
					language.removeChild(languages[j]);
				}
			}
			var link = languages[i].getElementsByTagName("a")[0];
			var root = document.createElement("span");
			root.innerHTML = link.innerHTML;
			languages[i].removeChild(link);
			languages[i].appendChild(root);
			languages[i].appendChild(submenu);

			languages[i].style.display = "block";
			languages[i].addEventListener("click", function () {
				if (document.documentElement.clientWidth < 992) {
					var current = languages[i].getAttribute( 'aria-expanded');
					if (current === 'true') {
						languages[i].setAttribute( 'aria-expanded', 'false' );
					} else {
						languages[i].setAttribute( 'aria-expanded', 'true' );
					}
				}
			});
			break;
		}
	}
} )();
