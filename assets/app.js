import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import "./styles/app.css";
import "select2";
import "select2/dist/css/select2.min.css";
$(document).ready(function () {
  $(".select2").select2();
});
console.log("This log comes from assets/app.js - welcome to AssetMapper! 🎉");
