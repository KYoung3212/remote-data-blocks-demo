# Remote Data Blocks Demo (WordPress VIP)

Connect live data from APIs, Google Sheets, Shopify, and more, and it stays up to date on every page that uses it. This demo runs entirely in WordPress Playground with no credentials needed.

[![Open in WordPress Playground](https://img.shields.io/badge/Open%20in-WordPress%20Playground-3858e9?logo=wordpress)](https://playground.wordpress.net/?blueprint-url=https%3A%2F%2Fraw.githubusercontent.com%2FKYoung3212%2Fremote-data-blocks-demo%2Frefs%2Fheads%2Fmain%2Fplayground%2Fblueprint.json)

## What's inside

| Block | Data source | Try |
|---|---|---|
| State Weather | api.weather.gov | Pick any of the 50 states (forecast for the capital) |
| Zip Code | zippopotam.us | Enter any US zip code |
| Known Exploited Vulnerabilities | CISA KEV catalog | Search "JetBrains" |
| Shopify Store (Mock Shop) | mock.shop, connected in Settings with no code | Pick products |

The Playground opens on a **Start here** page that walks through the demo:

1. See the data sources under **Settings → Remote Data Blocks**.
2. View live data on the **Remote Data Block Gallery** page.
3. Edit the gallery page and see which values are locked.
4. Build your own blocks on the **Try it** page.

## Files

- `remote-data-blocks-demo.php`: the plugin that registers the demo blocks
- `playground/blueprint.json`: the Playground setup (installs Remote Data Blocks and this plugin, connects the Shopify mock store, and creates the demo pages)

## Learn more

- Docs: https://remotedatablocks.com
- Plugin source and more examples: https://github.com/Automattic/remote-data-blocks
