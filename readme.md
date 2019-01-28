# TimeSeries

This is an absurd little app demonstrating different time-series prediction algorithms.

The absurd detail is that it is a really clean, extensible, little app which uses PHP for its intended purpose: rendering HTML. There's no data layer, no view layer, no templating language. PHP is the templating language.

---

To add a new page to the app, simply create a file under the `pages/` directory.

`forecast.php` exposes two functions, `deriveError` and `renderData` which format data and then parse that data into a standard HTML table.
