# Hàm đọc file giống FileIO.readFile trong Java
def read_file(path):
    with open(path, "rb") as f:
        return f.read()

# Đọc dữ liệu
dart = read_file("data/update_data/dart")
arrow = read_file("data/update_data/arrow")
effect = read_file("data/update_data/effect")
image = read_file("data/update_data/image")
part = read_file("data/update_data/part")
skill = read_file("data/update_data/skill")

# --- Dùng dữ liệu ---
# Ví dụ: gửi dữ liệu qua socket, xử lý, v.v.
print(f"Đã nạp {len(dart)} bytes dữ liệu dart")
print(f"Đã nạp {len(arrow)} bytes dữ liệu arrow")
print(f"Đã nạp {len(effect)} bytes dữ liệu effect")
print(f"Đã nạp {len(image)} bytes dữ liệu image")
print(f"Đã nạp {len(part)} bytes dữ liệu part")
print(f"Đã nạp {len(skill)} bytes dữ liệu skill")
