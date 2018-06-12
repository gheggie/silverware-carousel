# SilverWare Carousel Module

[![Latest Stable Version](https://poser.pugx.org/silverware/carousel/v/stable)](https://packagist.org/packages/silverware/carousel)
[![Latest Unstable Version](https://poser.pugx.org/silverware/carousel/v/unstable)](https://packagist.org/packages/silverware/carousel)
[![License](https://poser.pugx.org/silverware/carousel/license)](https://packagist.org/packages/silverware/carousel)

Provides a carousel component for use with [SilverWare][silverware].

## Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Usage](#usage)
- [Issues](#issues)
- [Contribution](#contribution)
- [Attribution](#attribution)
- [Maintainers](#maintainers)
- [License](#license)

## Requirements

- [SilverWare][silverware]

## Installation

Installation is via [Composer][composer]:

```
$ composer require silverware/carousel
```

## Usage

This module provides a `CarouselComponent` which can be added to your SilverWare templates or
layouts using the CMS.

### Carousel Component

The `CarouselComponent` represents a standard [Bootstrap carousel][bootstrap-carousel]. Slides
may added to the carousel by adding either a slide or a list source to the carousel using the CMS.

The dimensions and resize method used for slides is configured using the Style tab of the component.
On the Options tab, you can define the slide interval in milliseconds, and whether or not to show
the carousel controls and indicators.

### Slides

Slides are added as children of the component using the site tree. Each slide may have an image,
caption, and a link to either a page within the CMS, or a URL. Slides will appear in the order defined
within the site tree.

### List Sources

A special type of slide is a "List Source" slide. This type of slide allows you to choose a list source
from within your CMS (e.g. blog, gallery etc.) and render the list items as slides within the carousel.
Each item object must make use of the SilverWare `MetaDataExtension` and the `ListItem` trait. Only
items which have an image provided via `getMetaImage` are shown.

## Issues

Please use the [GitHub issue tracker][issues] for bug reports and feature requests.

## Contribution

Your contributions are gladly welcomed to help make this project better.
Please see [contributing](CONTRIBUTING.md) for more information.

## Attribution

- Makes use of [Bootstrap](https://github.com/twbs/bootstrap) by the
  [Bootstrap Authors](https://github.com/twbs/bootstrap/graphs/contributors)
  and [Twitter, Inc](https://twitter.com).

## Maintainers

[![Colin Tucker](https://avatars3.githubusercontent.com/u/1853705?s=144)](https://github.com/colintucker) | [![Praxis Interactive](https://avatars2.githubusercontent.com/u/1782612?s=144)](https://www.praxis.net.au)
---|---
[Colin Tucker](https://github.com/colintucker) | [Praxis Interactive](https://www.praxis.net.au)

## License

[BSD-3-Clause](LICENSE.md) &copy; Praxis Interactive

[silverware]: https://github.com/praxisnetau/silverware
[composer]: https://getcomposer.org
[bootstrap-carousel]: https://v4-alpha.getbootstrap.com/components/carousel
[issues]: https://github.com/praxisnetau/silverware-carousel/issues
