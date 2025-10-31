package models.npc.npc_list;

import consts.BossID;
import consts.ConstNpc;
import database.daos.NDVSqlFetcher;
import database.daos.TraningDAO;
import models.item.Item;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;

import models.player.Traning;
import network.io.Message;
import models.npc.Npc;
import models.player.Player;
import services.player.InventoryService;
import services.Service;
import services.SkillService;
import services.map.ChangeMapService;
import services.CombineService;
import services.dungeon.TrainingService;
import services.map.NpcService;
import services.ShopService;
import models.skill.Skill;
import utils.SkillUtil;
import utils.Util;

public class Whis extends Npc {

    private static final int COST_HD = 50000000;

    public Whis(int mapId, int status, int cx, int cy, int tempId, int avartar) {
        super(mapId, status, cx, cy, tempId, avartar);
    }

    @Override
    public void openBaseMenu(Player player) {
        if (canOpenNpc(player)) {
            switch (this.mapId) {
                case 154 -> {
                    Item BiKiepTuyetKy = InventoryService.gI().findItem(player.inventory.itemsBag, 1229);
                    if (BiKiepTuyetKy != null) {
                        createOtherMenu(player, ConstNpc.BASE_MENU,
                                "Thử đánh với ta xem nào.\nNgươi còn 1 lượt nữa cơ mà.",
                                "Nói chuyện",
                                "Học\ntuyệt kỹ",
                                "Top 100",
                                "[LV:" + (player.traning.getTop() + 1) + "]",
                                "Đến vùng đất hủy diệt");
                    } else {
                        createOtherMenu(player, ConstNpc.BASE_MENU,
                                "Thử đánh với ta xem nào.\nNgươi còn 1 lượt nữa cơ mà.",
                                "Nói chuyện",
                                "Top 100",
                                "[LV:" + (player.traning.getTop() + 1) + "]",
                                "Đến vùng đất hủy diệt");
                    }
                }
                case 164 ->
                        this.createOtherMenu(player, ConstNpc.BASE_MENU, "Ta có thể giúp gì cho ngươi ?",
                                "Quay về", "Từ chối");
                case 169 ->
                        this.createOtherMenu(player, ConstNpc.BASE_MENU,
                                "Cậu không chịu nổi khi ở đây sao?\nCậu sẽ khó mà mạnh lên được",
                                "Trốn về", "Ở lại");
                case 48 ->
                        this.createOtherMenu(player, ConstNpc.BASE_MENU,
                                "Đã tìm đủ nguyên liệu cho tôi chưa?\n Tôi sẽ giúp cậu mạnh lên kha khá đấy!",
                                "Hướng Dẫn", "Đổi SKH VIP", "Chế tạo\nChân mệnh", "Nâng cấp\nChân mệnh", "Từ Chối");
            }
        }
    }

    @Override
    public void confirmMenu(Player player, int select) {
        if (canOpenNpc(player)) {
            if (player.idMark.isBaseMenu() && this.mapId == 154) {
                handleMap154BaseMenu(player, select);
            }
            else if (player.idMark.isBaseMenu() && this.mapId == 169) {
                handleMap169BaseMenu(player, select);
            }
            else if (player.idMark.getIndexMenu() == 5) {
                if (select == 0) {
                    ShopService.gI().opendShop(player, "THIEN_SU", true);
                }
            }
            else if (player.idMark.getIndexMenu() == 6) {
                if (select == 0) {
                    handleLearnSkill(player);
                }
            }
            else if (player.idMark.isBaseMenu() && this.mapId == 48) {
                if (select == 0) {
                    NpcService.gI().createTutorial(player, tempId, this.avartar, ConstNpc.HUONG_DAN_DOI_SKH_VIP);
                }
            }
            else if (player.idMark.getIndexMenu() == ConstNpc.MENU_DAP_DO ||
                    player.idMark.getIndexMenu() == ConstNpc.MENU_NANG_DOI_SKH_VIP ||
                    player.idMark.getIndexMenu() == ConstNpc.MENU_CHE_TAO_CHAN_MENH ||
                    player.idMark.getIndexMenu() == ConstNpc.MENU_NANG_CAP_CHAN_MENH) {
                if (select == 0) {
                    CombineService.gI().startCombine(player);
                }
            }
        }
    }

    private void handleMap154BaseMenu(Player player, int select) {
        Item BiKiepTuyetKy = InventoryService.gI().findItem(player.inventory.itemsBag, 1229);
        boolean hasBook = (BiKiepTuyetKy != null);

        // Xác định option thực tế
        int actualOption = select;
        if (!hasBook && select > 0) {
            actualOption = select + 1;
        }

        switch (actualOption) {
            case 0 -> {
                // Nói chuyện
                if (!player.setClothes.checkSetDes()) {
                    this.createOtherMenu(player, ConstNpc.IGNORE_MENU,
                            "Ngươi hãy trang bị đủ 5 món trang bị Hủy Diệt rồi ta nói chuyện tiếp.",
                            "OK");
                    return;
                }
                this.createOtherMenu(player, 5,
                        "Ta sẽ giúp ngươi chế tạo trang bị thiên sứ",
                        "Shop thiên sứ",
                        "Chế tạo",
                        "Từ chối");
            }
            case 1 -> {
                // Học tuyệt kỹ (chỉ khi có sách)
                if (hasBook) {
                    openLearnSkillMenu(player);
                } else {
                    // Nếu không có sách, đây là Top 100
                    topWhis(player);
                }
            }
            case 2 -> {

                    topWhis(player);

            }
            case 3 -> {

                    TrainingService.gI().callBoss(player, BossID.WHIS, false);

                    // Vào map HD (khi không có sách)

            }
            case 4 -> {
                // Vào map HD (chỉ khi có sách)
                if (hasBook) {
                    vaoMapHD(player);
                }
            }
        }
    }

    private void handleMap169BaseMenu(Player player, int select) {
        switch (select) {
            case 0 -> {
                // Trốn về
                ChangeMapService.gI().changeMapBySpaceShip(player, 154, -1, 450);
            }
            case 1 -> {
                // Ở lại
                npcChat(player, "Không có gì phải sợ");
            }
        }
    }

    private void openLearnSkillMenu(Player player) {
        Item BiKiepTuyetKy = InventoryService.gI().findItem(player.inventory.itemsBag, 1229);
        if (BiKiepTuyetKy == null) {
            Service.gI().sendThongBao(player, "Bạn không có bí kiếp tuyệt kỹ");
            return;
        }

        int idskill = Skill.MA_PHONG_BA;
        if (player.gender == 0) {
            idskill = Skill.SUPER_KAME;
        } else if (player.gender == 2) {
            idskill = Skill.LIEN_HOAN_CHUONG;
        }

        Skill curSkill = SkillUtil.getSkillbyId(player, idskill);
        boolean isNewSkill = (curSkill == null || curSkill.point == 0);

        if (isNewSkill) {
            if (player.gender == 0) {
                this.createOtherMenu(player, 6, "|1|Ta sẽ dạy ngươi tuyệt kỹ Super kamejoko 1\n|7|Bí kiếp tuyệt kỹ: " + BiKiepTuyetKy.quantity + "/9999\n" + "|2|Giá vàng: 10.000.000\n" + "|2|Giá ngọc: 99",
                        "Đồng ý", "Từ chối");
            } else if (player.gender == 1) {
                this.createOtherMenu(player, 6, "|1|Ta sẽ dạy ngươi tuyệt kỹ Ma phong ba 1\n|7|Bí kiếp tuyệt kỹ: " + BiKiepTuyetKy.quantity + "/9999\n" + "|2|Giá vàng: 10.000.000\n" + "|2|Giá ngọc: 99",
                        "Đồng ý", "Từ chối");
            } else {
                this.createOtherMenu(player, 6, "|1|Ta sẽ dạy ngươi tuyệt kỹ Ca đíc liên hoàn chưởng 1\n|7|Bí kiếp tuyệt kỹ: " + BiKiepTuyetKy.quantity + "/9999\n" + "|2|Giá vàng: 10.000.000\n" + "|2|Giá ngọc: 99",
                        "Đồng ý", "Từ chối");
            }
        } else {
            if (player.gender == 0) {
                this.createOtherMenu(player, 6, "|1|Ta sẽ dạy ngươi tuyệt kỹ Super kamejoko " + (curSkill.point + 1) + "\n" + "|7|Bí kiếp tuyệt kỹ: " + BiKiepTuyetKy.quantity + "/999\n" + "|2|Giá vàng: 10.000.000\n" + "|2|Giá ngọc: 99",
                        "Đồng ý", "Từ chối");
            } else if (player.gender == 1) {
                this.createOtherMenu(player, 6, "|1|Ta sẽ dạy ngươi tuyệt kỹ Ma phong ba " + (curSkill.point + 1) + "\n" + "|7|Bí kiếp tuyệt kỹ: " + BiKiepTuyetKy.quantity + "/999\n" + "|2|Giá vàng: 10.000.000\n" + "|2|Giá ngọc: 99",
                        "Đồng ý", "Từ chối");
            } else {
                this.createOtherMenu(player, 6, "|1|Ta sẽ dạy ngươi tuyệt kỹ Ca đíc liên hoàn chưởng " + (curSkill.point + 1) + "\n" + "|7|Bí kiếp tuyệt kỹ: " + BiKiepTuyetKy.quantity + "/999\n" + "|2|Giá vàng: 10.000.000\n" + "|2|Giá ngọc: 99",
                        "Đồng ý", "Từ chối");
            }
        }
    }

    private void handleLearnSkill(Player player) {
        Item sach = InventoryService.gI().findItemBag(player, 1229);
        if (sach == null || player.inventory.gold < 10000000 || player.inventory.gem <= 99 || player.nPoint.power < 60000000000L) {
            if (player.nPoint.power < 60000000000L) {
                Service.gI().sendThongBao(player, "Ngươi không đủ sức mạnh để học tuyệt kỹ");
            } else if (player.inventory.gold < 10000000) {
                Service.gI().sendThongBao(player, "Hãy có đủ vàng thì quay lại gặp ta.");
            } else if (player.inventory.gem <= 99) {
                Service.gI().sendThongBao(player, "Hãy có đủ ngọc xanh thì quay lại gặp ta.");
            }
            return;
        }

        int idskill = Skill.MA_PHONG_BA;
        int iconSkill = 11194;
        if (player.gender == 0) {
            idskill = Skill.SUPER_KAME;
            iconSkill = 11162;
        } else if (player.gender == 2) {
            idskill = Skill.LIEN_HOAN_CHUONG;
            iconSkill = 11193;
        }

        Skill curSkill = SkillUtil.getSkillbyId(player, idskill);
        boolean isNewSkill = (curSkill == null || curSkill.point == 0);

        if (isNewSkill) {
            // Học skill mới
            if (sach.quantity < 9999) {
                int sosach = 9999 - sach.quantity;
                Service.gI().sendThongBao(player, "Ngươi còn thiếu " + sosach + " bí kíp nữa.\nHãy tìm đủ rồi đến gặp ta.");
                return;
            }

            try {
                boolean success = Util.isTrue(85, 100);
                int trubk = success ? 9999 : 99;
                String msg = success ? "Học skill thành công!" : "Tư chất kém!";
                String msg2 = success ? "Chúc mừng con nhé!" : "Ngu dốt!";

                if (success) {
                    switch (player.gender) {
                        case 0 -> SkillService.gI().learSkillSpecial(player, Skill.SUPER_KAME);
                        case 2 -> SkillService.gI().learSkillSpecial(player, Skill.LIEN_HOAN_CHUONG);
                        default -> SkillService.gI().learSkillSpecial(player, Skill.MA_PHONG_BA);
                    }
                } else {
                    iconSkill = 15313;
                }

                sendSkillEffect(player, sach, iconSkill, trubk, msg2);
                Service.gI().sendThongBao(player, msg);
                InventoryService.gI().subQuantityItemsBag(player, sach, trubk);
                player.inventory.gold -= 10000000;
                player.inventory.gem -= 99;
                InventoryService.gI().sendItemBags(player);
                Service.gI().sendMoney(player);

            } catch (IOException e) {
                e.printStackTrace();
            }
        } else {
            // Nâng cấp skill
            if (sach.quantity < 999) {
                int sosach = 999 - sach.quantity;
                Service.gI().sendThongBao(player, "Ngươi còn thiếu " + sosach + " bí kíp nữa.\nHãy tìm đủ rồi đến gặp ta.");
                return;
            }

            if (curSkill.currLevel < 1000) {
                npcChat(player, "Ngươi chưa luyện skill đến mức thành thạo. Luyện thêm đi.");
                return;
            }

            if (curSkill.point >= 9) {
                npcChat(player, "Skill của ngươi đã đến cấp độ tối đa");
                return;
            }

            try {
                boolean success = Util.isTrue(85, 100);
                int trubk = success ? 999 : 99;
                String msg = success ? "Nâng skill thành công!" : "Tư chất kém!";
                String msg2 = success ? "Chúc mừng con nhé!" : "Ngu dốt!";

                if (success) {
                    curSkill.point++;
                    curSkill.currLevel = 0;
                    SkillService.gI().sendCurrLevelSpecial(player, curSkill);
                } else {
                    iconSkill = 15313;
                }

                sendSkillEffect(player, sach, iconSkill, trubk, msg2);
                Service.gI().sendThongBao(player, msg);
                InventoryService.gI().subQuantityItemsBag(player, sach, trubk);
                player.inventory.gold -= 10000000;
                player.inventory.gem -= 99;
                InventoryService.gI().sendItemBags(player);
                Service.gI().sendMoney(player);

            } catch (IOException e) {
                e.printStackTrace();
            }
        }
    }

    private void sendSkillEffect(Player player, Item sach, int iconSkill, int trubk, String msg2) throws IOException {
        Message msgg = new Message(-81);
        msgg.writer().writeByte(0);
        msgg.writer().writeUTF("Skill 9");
        msgg.writer().writeShort(tempId);
        player.sendMessage(msgg);
        msgg.cleanup();

        msgg = new Message(-81);
        msgg.writer().writeByte(1);
        msgg.writer().writeByte(1);
        msgg.writer().writeByte(InventoryService.gI().getIndexItemBag(player, sach));
        player.sendMessage(msgg);
        msgg.cleanup();

        msgg = new Message(-81);
        msgg.writer().writeByte(trubk == 99 ? 8 : 7);
        msgg.writer().writeShort(iconSkill);
        player.sendMessage(msgg);
        msgg.cleanup();

        this.npcChat(player, msg2);
    }

    private void vaoMapHD(Player player) {
        if (player.nPoint.power >= 80000000000L && player.inventory.gold >= COST_HD) {
            player.inventory.gold -= COST_HD;
            Service.gI().sendMoney(player);
            ChangeMapService.gI().changeMapBySpaceShip(player, 169, -1, 168);
        } else {
            this.npcChat(player, "Bạn chưa đủ điều kiện để vào");
            Service.gI().sendThongBao(player, "Yêu cầu sức mạnh lớn hơn 80 Tỷ và 50 Tr vàng.");
        }
    }
    private void topWhis(Player player) {
        Message msg = null;
        try {
            List<Traning> tranings = TraningDAO.getTopTraning();

            // Lọc bỏ những entry có level = 0
            List<Traning> filteredTranings = new ArrayList<>();
            for (Traning training : tranings) {
                if (training.getTop() > 0) {
                    filteredTranings.add(training);
                }
            }

            // Giới hạn tối đa 100 người
            if (filteredTranings.size() > 100) {
                filteredTranings = filteredTranings.subList(0, 100);
            }

            msg = new Message(-96);
            msg.writer().writeByte(0);
            msg.writer().writeUTF("Top 100 Cao Thủ");
            msg.writer().writeByte(filteredTranings.size());

            for (int i = 0; i < filteredTranings.size(); i++) {
                Traning p = filteredTranings.get(i);
                msg.writer().writeInt(p.getTopWhis());
                msg.writer().writeInt((int) p.getPlayerID());

                Player listPlayer = NDVSqlFetcher.loadById(p.getPlayerID());
                if (listPlayer != null) {
                    msg.writer().writeShort(listPlayer.getHead());
                    if (player.getSession().version >= 214) {
                        msg.writer().writeShort(-1);
                    }
                    msg.writer().writeShort(listPlayer.getBody());
                    msg.writer().writeShort(listPlayer.getLeg());
                } else {
                    msg.writer().writeShort(0);
                    if (player.getSession().version >= 214) {
                        msg.writer().writeShort(-1);
                    }
                    msg.writer().writeShort(0);
                    msg.writer().writeShort(0);
                }

                msg.writer().writeUTF(p.getName());
                msg.writer().writeUTF("Lv:" + p.getTop() + " với " + String.format("%.2f", p.getTime() / 1000.0).replace(".", ",") + " giây");
                msg.writer().writeUTF("");
            }

            player.sendMessage(msg);
            msg.cleanup();

        } catch (Exception e) {
            e.printStackTrace();
        } finally {
            if (msg != null) {
                msg.cleanup();
            }
        }
    }
}