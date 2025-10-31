package models.npc.npc_list;




import consts.ConstNpc;
import models.npc.Npc;
import models.player.Player;
import services.ShopService;

public class Santa extends Npc {

    public Santa(int mapId, int status, int cx, int cy, int tempId, int avartar) {
        super(mapId, status, cx, cy, tempId, avartar);
    }

    @Override
    public void openBaseMenu(Player player) {
        if (canOpenNpc(player)) {
            if (!player.inventory.itemsDaBan.isEmpty()) {
                createOtherMenu(player, ConstNpc.BASE_MENU,
                        "Xin chào, ta có một số vật phẩm đặt biệt cậu có muốn xem không?",
                        "Cửa hàng", "Mua lại vật phẩm đã bán", "Hộp Quà\nEvent 20/11","Danh hiệu", "Tiệm Hớt Tóc");
            } else {
                createOtherMenu(player, ConstNpc.BASE_MENU,
                        "Xin chào, ta có một số vật phẩm đặt biệt cậu có muốn xem không?",
                        "Cửa hàng", "Hộp Quà\nEvent 20/11", "Danh hiệu" ,"Tiệm Hớt Tóc");
            }
        }
    }

    @Override
    public void confirmMenu(Player player, int select) {
        if (canOpenNpc(player)) {
            if (this.mapId == 5 || this.mapId == 13 || this.mapId == 20) {
                if (player.idMark.isBaseMenu()) {
                    switch (select) {
                        case 0 ->
                            ShopService.gI().opendShop(player, "SANTA", false);
                        case 1 -> {
                            if (!player.inventory.itemsDaBan.isEmpty()) {
                                ShopService.gI().opendShop(player, "ITEMS_DABAN", false);
                            } else {
                                ShopService.gI().opendShop(player, "SANTA_EVENT", false);
                            }
                        }
                        case 2 -> {
                            if (!player.inventory.itemsDaBan.isEmpty()) {
                                ShopService.gI().opendShop(player, "SANTA_EVENT", false);
                            } else {
                                ShopService.gI().opendShop(player, "SANTA_DANH_HIEU", false);
                            }
                        }
                        case 3 -> {
                            if (!player.inventory.itemsDaBan.isEmpty()) {
                                ShopService.gI().opendShop(player, "SANTA_DANH_HIEU", false);
                            } else {
                                ShopService.gI().opendShop(player, "SANTA_HEAD", false);
                            }
                        }

                        case 4->
                            ShopService.gI().opendShop(player, "SANTA_HEAD", false);
                    }
                }
            }
        }
    }
}
