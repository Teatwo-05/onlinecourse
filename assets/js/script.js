// Main JavaScript file
document.addEventListener('DOMContentLoaded', function(){
	// Password toggle (button with data-toggle)
	document.querySelectorAll('[data-toggle="pw"]').forEach(function(btn){
		btn.addEventListener('click', function(){
			var target = document.querySelector(btn.getAttribute('data-target'));
			if (!target) return;
			if (target.type === 'password') { target.type = 'text'; btn.textContent = 'Hide'; }
			else { target.type = 'password'; btn.textContent = 'Show'; }
		});
	});

	// Password confirmation check for forms with data-confirm attribute
	document.querySelectorAll('form[data-confirm]').forEach(function(form){
		var pw = form.querySelector('input[name="password"]');
		var pwc = form.querySelector('input[name="password_confirm"]');
		var submit = form.querySelector('button[type="submit"]');
		var strengthBar = form.querySelector('.pw-strength > i');

		function checkMatch(){
			if (!pw || !pwc) return true;
			if (pw.value !== pwc.value){
				pwc.setCustomValidity('Mật khẩu không trùng');
			} else { pwc.setCustomValidity(''); }
		}

		function updateStrength(){
			if (!pw || !strengthBar) return;
			var val = pw.value;
			var score = 0;
			if (val.length >= 8) score += 1;
			if (/[A-Z]/.test(val)) score += 1;
			if (/[0-9]/.test(val)) score += 1;
			if (/[^A-Za-z0-9]/.test(val)) score += 1;
			var pct = Math.min(100, (score / 4) * 100);
			strengthBar.style.width = pct + '%';
			if (pct < 34) strengthBar.style.background = '#ff5f6d';
			else if (pct < 67) strengthBar.style.background = '#ffb74d';
			else strengthBar.style.background = '#6be56b';
		}

		if (pw){ pw.addEventListener('input', function(){ checkMatch(); updateStrength(); }); }
		if (pwc) pwc.addEventListener('input', checkMatch);
		if (form){ form.addEventListener('submit', function(e){ checkMatch(); if (!form.checkValidity()){ e.preventDefault(); } }); }
	});
});
