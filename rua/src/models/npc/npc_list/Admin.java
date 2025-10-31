package models.npc.npc_list;

import consts.ConstNpc;
import database.daos.NDVSqlFetcher;
import models.npc.Npc;
import models.player.Player;
import models.rank.RankInfo;

import network.io.Message;
import utils.Logger;
import database.daos.RankDAO;


import java.util.List;

public class Admin extends Npc {

    public Admin(int mapId, int status, int cx, int cy, int tempId, int avartar) {
        super(mapId, status, cx, cy, tempId, avartar);
    }

    @Override
    public void openBaseMenu(Player player) {
        if (canOpenNpc(player)) {
            this.createOtherMenu(player, ConstNpc.BASE_MENU,
                    "Hello Anh Em hi !!!!",
                    "Top Sức Mạnh", "Top Nhiệm Vụ", "Top Nạp", "Nhận Thưởng");
        }
    }

    @Override
    public void confirmMenu(Player player, int select) {
        if (canOpenNpc(player)) {
            if (player.idMark.isBaseMenu()) {
                switch (select) {
                    case 0: // Top Sức Mạnh
                        topSucManh(player);
                        break;
                    case 1: // Top Nhiệm Vụ
                        TopNhiemVu(player);
                        break;
                    case 2: // Top Nạp

                        break;
                    case 3: // Nhận Thưởng
                        // TODO: thêm logic nhận thưởng
                        break;
                }
            }
        }
    }
    public void topSucManh(Player player) {
        long st = System.currentTimeMillis();
        Message msg = null;
        try {
            // Lấy top 100 sức mạnh
            List<RankInfo> list = RankDAO.getTopSucManh(100);

            msg = new Message(-96);
            msg.writer().writeByte(0); // type bảng
            msg.writer().writeUTF("Top 100 Sức Mạnh");
            msg.writer().writeByte(list.size());

            for (RankInfo r : list) {
                Player listPlayer = NDVSqlFetcher.loadById(r.getId());
                msg.writer().writeInt(r.getRank());       // thứ hạng
                msg.writer().writeInt((int) r.getId());   // id player

                // ===== Thêm xử lý hợp thể bông tai cấp 3 =====
                short head = listPlayer.getHead();
                msg.writer().writeShort(head);

                if (player.getSession().version >= 214) {
                    msg.writer().writeShort(-1); // tùy version client
                }

                msg.writer().writeShort(listPlayer.getBody());
                msg.writer().writeShort(listPlayer.getLeg());
                msg.writer().writeUTF(r.getName());       // tên
                msg.writer().writeUTF("");                // status (có thể để rỗng hoặc custom)
                msg.writer().writeUTF(r.getInfo());       // info (sức mạnh hiển thị)
            }

            player.sendMessage(msg);
            msg.cleanup();

            for (RankInfo r : list) {
                r.dispose();
            }
            list.clear();
        } catch (Exception e) {
            e.printStackTrace();
        } finally {
            if (msg != null) {
                msg.cleanup();
            }
        }
    }

    public void TopNhiemVu(Player player) {
        long st = System.currentTimeMillis();
        RankDAO.loadTasks();
        Message msg = null;
        try {
            // Lấy top 100 sức mạnh
            List<RankInfo> list = RankDAO.getTopTask( 100);

            msg = new Message(-96);
            msg.writer().writeByte(0); // type bảng
            msg.writer().writeUTF("Top 100 Nhiêm vụ");
            msg.writer().writeByte(list.size());

            for (RankInfo r : list) {
                Player listPlayer = NDVSqlFetcher.loadById(r.getId());
                msg.writer().writeInt(r.getRank());       // thứ hạng
                msg.writer().writeInt((int) r.getId());   // id player
                msg.writer().writeShort(listPlayer.getHead());     // head
                if (player.getSession().version >= 214) {
                    msg.writer().writeShort(-1); // tùy version client
                }
                msg.writer().writeShort(listPlayer.getBody());
                msg.writer().writeShort(listPlayer.getLeg());
                msg.writer().writeUTF(r.getName());       // tên
                msg.writer().writeUTF("");                // status (có thể để rỗng hoặc custom)
                msg.writer().writeUTF(r.getInfo());       // info (sức mạnh hiển thị)
            }

            player.sendMessage(msg);
            msg.cleanup();

            for (RankInfo r : list) {
                r.dispose();
            }
            list.clear();
        } catch (Exception e) {
            e.printStackTrace();
        } finally {
            if (msg != null) {
                msg.cleanup();
            }
        }
    }


}
