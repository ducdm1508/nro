package models.npc.npc_list;




import consts.ConstNpc;
import models.item.Item;
import models.npc.Npc;
import models.player.Player;
import server.Maintenance;
import server.Manager;
import services.player.InventoryService;
import services.ItemService;
import services.Service;
import services.TaskService;
import services.func.Input;
import utils.Util;

public class OngGohan extends Npc {

    public OngGohan(int mapId, int status, int cx, int cy, int tempId, int avartar) {
        super(mapId, status, cx, cy, tempId, avartar);
    }

    @Override
    public void openBaseMenu(Player player) {
        if (canOpenNpc(player)) {
            if (!TaskService.gI().checkDoneTaskTalkNpc(player, this)) {
                this.createOtherMenu(player, ConstNpc.BASE_MENU,
                        "Ngọc Rồng " + Manager.Name_SV + " Server Hoàn Toàn Miễn Phí",
                        "Đổi Mật Khẩu", "Nhận 2m ngọc xanh", "Nhận\nVàng", "Giftcode", "Quên Mã Bảo Vệ?");

            }
        }
    }

    @Override
    public void confirmMenu(Player player, int select) {
        if (canOpenNpc(player)) {
            if (player.idMark.isBaseMenu()) {
                switch (select) {
                    case 0 ->
                        Input.gI().createFormChangePassword(player);
                    case 1 -> {
                        if (player.inventory.gem >= 20_000_000) {
                            this.npcChat(player, "Tham Lam!");
                            break;
                        }
                        player.inventory.gem += 100000;
                        Service.gI().sendMoney(player);
                        Service.gI().sendThongBao(player, "Bạn vừa nhận được 100000 ngọc xanh!");
                    }
                    case 2 -> {
                        if (Maintenance.isRunning) {
                            break;
                        }
                        if (!Util.isAfterMidnight(player.lastRewardGoldBarTime) && !player.isAdmin()) {
                            Service.gI().sendThongBaoOK(player, "Hãy chờ đến ngày mai");
                            this.npcChat(player, "Chỉ có làm mới có ăn, không làm mà đòi có ăn chỉ có ăn đb, ăn c*t thôi con nhé!");
                            return;
                        }
                        if (InventoryService.gI().getCountEmptyBag(player) > 0) {
                            int quantity = player.danhanthoivang ? (player.getSession().vip > 0 ? 10000 : 3000) : 10000;
                            if (player.isAdmin()) {
                                quantity = 2_000_000_000;
                            }
                            Item goldBar = ItemService.gI().createNewItem((short) 457, quantity);
                            InventoryService.gI().addItemBag(player, goldBar);
                            InventoryService.gI().sendItemBags(player);
                            this.npcChat(player, "Ta đã gửi " + quantity + " thỏi vàng vào hành trang của con\n con hãy kiểm tra ");
                            player.danhanthoivang = true;
                            player.lastRewardGoldBarTime = System.currentTimeMillis();
                        } else {
                            this.npcChat(player, "Hãy chừa cho ta 1 ô trống");
                        }
                    }
                    case 3 ->
                        Input.gI().createFormGiftCode(player);
                    case 4 ->
                        Input.gI().createFormMBV(player);
                }
            }
        }
    }
}
