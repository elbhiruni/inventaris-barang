# Inventaris Barang

## Requirements

- PHP ^8.5
- composer ^2.8.12
- node ^24.22.2
- pnpm
- PostgreSQL

## Setup

- Bikin database baru dengan nama inventaris_barang pada PostgreSQL
- copy paste file .env.example dan beri nama .env
- isi DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD pada .env sesuai settingan masing" device
- `composer run setup`
- `composer run dev`
