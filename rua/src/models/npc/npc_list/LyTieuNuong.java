package models.npc.npc_list;



import consts.ConstNpc;
import models.npc.Npc;
import models.player.Player;
import services.TaskService;

public class LyTieuNuong extends Npc {

    public LyTieuNuong(int mapId, int status, int cx, int cy, int tempId, int avartar) {
        super(mapId, status, cx, cy, tempId, avartar);
    }

    @Override
    public void openBaseMenu(Player player) {
        if (!TaskService.gI().checkDoneTaskTalkNpc(player, this)) {
            createOtherMenu(player, 0, "Không có gì ở đây cả", "Ok");
        }
    }

    @Override
    public void confirmMenu(Player pl, int select) {
        if (canOpenNpc(pl)) {
            switch (pl.idMark.getIndexMenu()) {
                case 0 -> {
                    switch (select) {
                        case 0 ->
                            createOtherMenu(pl, ConstNpc.IGNORE_MENU, "Chúc anh em chơi game vui vẻ ", "Ok");
                        default -> {
                        }
                    }
                }
            }
        }
    }
}
