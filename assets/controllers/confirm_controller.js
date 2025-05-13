import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  confirmDelete(event) {
    if (!confirm("Are you sure you want to delete this item?")) {
      event.preventDefault();
    }
  }
}
