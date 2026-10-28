const res = await fetch('http://localhost/contact-2/');
const html = await res.text();
const m = html.match(/<style id="contact-page-custom-styles">([\s\S]*?)<\/style>/);
if (m) {
  console.log("Found style block! Length:", m[1].length);
  const containerRule = m[1].match(/[^{}]*\.container[^{}]*\{[^}]*\}/g);
  console.log("Container rules in contact-page-custom-styles:", containerRule);
} else {
  console.log("No custom style block found!");
}
