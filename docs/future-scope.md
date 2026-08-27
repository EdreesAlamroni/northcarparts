# Future Scope

This document describes planned features that are **not** part of the current MVP. No tables, routes, or UI exist for these yet.

## Vehicle compatibility

When approved, add normalized vehicle data and a pivot to products:

```
Product ──< product_vehicle_compatibility >── VehicleVariant ── VehicleModel ── VehicleMake
```

- `vehicle_makes` — e.g. HYUNDAI, TOYOTA
- `vehicle_models` — belongs to make
- `vehicle_variants` — year range, engine, etc.
- `product_vehicle_compatibility` — links NCP products to variants

The current `Product` model is standalone so this can attach without restructuring the catalog.

## External cross-references

Only if the client approves competitor/supplier reference data (MANN, Müller, MAHLE, Bosch, WIX, FRAM, UFI, TECNECO):

```
Product ──< product_cross_references (brand, reference_code)
```

The [ARWAD file](../docs/ARWAD_20221130full.xlsx) is a reference source for future import — not used in MVP.

## Product search

Future search by product code, OEM number, name, category, and cross-references. Index-friendly columns (`code`, `oem_number`) are already on `products`.

## Other out-of-scope items

- E-commerce, pricing, inventory
- Customer accounts
- VIN search
- Product comparison
