package models.npc.npc_list;



import consts.ConstNpc;
import models.item.Item;
import models.npc.Npc;
import models.player.Player;
import services.player.InventoryService;
import services.ItemService;
import services.Service;
import services.TaskService;
import services.ShopService;
import utils.Util;

public class BulmaTuongLai extends Npc {

    public BulmaTuongLai(int mapId, int status, int cx, int cy, int tempId, int avartar) {
        super(mapId, status, cx, cy, tempId, avartar);
    }

    @Override
    public void openBaseMenu(Player player) {
        if (canOpenNpc(player)) {
            if (this.mapId == 104 || this.mapId == 5) {
                if (!TaskService.gI().checkDoneTaskTalkNpc(player, this)) {
                    this.createOtherMenu(player, ConstNpc.BASE_MENU, "Hế lô bạn nhỏ", "Cửa hàng", "Đóng");
                }
            } else if (this.mapId == 102) {
                if (!TaskService.gI().checkDoneTaskTalkNpc(player, this)) {
                    this.createOtherMenu(player, ConstNpc.BASE_MENU, "Muốn làm tí inovar không?", "Cửa hàng", "Đóng");
                }
            }
        }
    }

    @Override
    public void confirmMenu(Player player, int select) {
        if (canOpenNpc(player)) {
            if (this.mapId == 104 || this.mapId == 5) {
                if (player.idMark.isBaseMenu()) {
                    if (select == 0) {
                        ShopService.gI().opendShop(player, "KARIN", true);
                    }
                }
            } else if (this.mapId == 102) {
                if (player.idMark.isBaseMenu()) {
                    switch (select) {
                        case 0 ->
                            ShopService.gI().opendShop(player, "BULMA_TL", true);
                        default -> {
                        }
                    }
                }
            }
        }
    }
}
