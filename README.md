# pseproductlisttype

**pseproductlisttype** is a PrestaShop module that adds a **Product Type** column in the back-office product list.  
It allows you to quickly identify if a product is:

- a **Simple product**
- a **Combination product** (with attributes)
- a **Pack of products**
- a **Virtual product** (downloadable)

Each type is displayed with a **colored badge**, making catalog management clearer and faster.

---

## Features

- Adds a **Product Type** column in the back-office product grid.
- Displays a colored badge for each type:

| Type        | Hex Color  | RGBA Color (0.5 opacity) | Text Color |
|------------|------------|--------------------------|------------|
| Simple     | `#FFC107`  | `rgba(255, 193, 7, 0.5)` | black      |
| Combination| `#28A745`  | `rgba(40, 167, 69, 0.5)` | black      |
| Pack       | `#007BFF`  | `rgba(0, 123, 255, 0.5)` | white      |
| Virtual    | `#FF5722`  | `rgba(255, 87, 34, 0.5)` | white      |

- Badge colors are **configurable via the module configuration** (`ps_configuration`).
- Automatic text contrast management (black or white) depending on badge color.
- Filter allows displaying only simple, combination, pack, or virtual products.

---

## Installation

```bash
git clone https://github.com/your-username/pseproductlisttype.git
```
```bash
composer install
```

## Compatibility

- PrestaShop 8.x → 9.x
- PHP 8.x
  
## Author
Pierre Sénéchal