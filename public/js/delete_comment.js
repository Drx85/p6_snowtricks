window.onload = () => {
	let links = document.querySelectorAll("[data-delete-twig]");
	for (link of links) {
		link.addEventListener("click", function (e) {
			e.preventDefault();
			if (confirm("Confirmer la suppression (immédiate et définitive) ?")) {
				fetch(this.getAttribute("href"), {
					method: "DELETE",
					headers: {
						"X-Requested-With": "XMLHttpRequest",
						"Content-Type": "application/json"
					},
					body: JSON.stringify({"_token": this.dataset.token})
				}).then(
					(response) => response.json()
				).then((data) => {
					if (data.success !== undefined) {
						let el = document.getElementById(data.success);
						el.remove();
					} else
						alert(data.error);
				}).catch(e => alert(e));
			}
		})
	}
}
