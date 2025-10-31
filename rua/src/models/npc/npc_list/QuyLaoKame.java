package models.npc.npc_list;

import models.clan.Clan;
import consts.ConstNpc;
import models.item.Item;
import java.util.ArrayList;
import models.dungeon.TreasureUnderSea;
import network.io.Message;
import services.*;
import services.dungeon.TreasureUnderSeaService;
import models.npc.Npc;
import static models.npc.NpcFactory.PLAYERID_OBJECT;
import models.player.Player;
import services.player.InventoryService;
import services.map.NpcService;
import services.map.ChangeMapService;
import services.func.Input;

import services.player.PlayerService;
import models.skill.Skill;
import utils.Logger;
import utils.SkillUtil;
import utils.TimeUtil;
import utils.Util;

public class QuyLaoKame extends Npc {

    public QuyLaoKame(int mapId, int status, int cx, int cy, int tempId, int avartar) {
        super(mapId, status, cx, cy, tempId, avartar);
    }

    @Override
    public void openBaseMenu(Player player) {
        Item ruacon = InventoryService.gI().findItemBag(player, 874);
        if (canOpenNpc(player)) {
            ArrayList<String> menu = new ArrayList<>();
            if (!player.canReward) {
                menu.add("Nói\nchuyện");
                if (ruacon != null && ruacon.quantity >= 1) {
                    menu.add("Giao\nRùa con");
                }
            } else {
                menu.add("Giao\nLân con");
            }
            String[] menus = menu.toArray(String[]::new);
            if (!TaskService.gI().checkDoneTaskTalkNpc(player, this)) {
                this.createOtherMenu(player, ConstNpc.BASE_MENU, "Con muốn hỏi gì nào?", menus);
            }
        }
    }

    @Override
    public void confirmMenu(Player player, int select) {
        if (!canOpenNpc(player)) return;
        if (player.canReward) {
            RewardService.gI().rewardLancon(player);
            return;
        }

        switch (player.idMark.getIndexMenu()) {
            case ConstNpc.BASE_MENU:
                if (select == 0) {
                    if (select == 0) {
                        if (player.LearnSkill.Time != -1 && player.LearnSkill.Time <= System.currentTimeMillis()) {
                            player.LearnSkill.Time = -1;
                            try {
                                int skillId = player.LearnSkill.ItemTemplateSkillId;
                                long requiredPotential = player.LearnSkill.Potential;

                                if (player.nPoint.tiemNang < player.LearnSkill.Potential) {
                                    long tnPotential = player.LearnSkill.Potential - player.nPoint.tiemNang;
                                    Service.gI().sendThongBao(player, "Bạn không đủ tiềm năng để học, Cần thêm "
                                            + Service.gI().formatTien(tnPotential) + " tiềm năng nữa.");
                                    return;
                                }

// Trừ tiềm năng khi học xong
                                player.nPoint.tiemNang -= player.LearnSkill.Potential;

// Tăng level skill và cập nhật
                                Skill curSkill = SkillUtil.getSkillByItemID(player, player.LearnSkill.ItemTemplateSkillId);
                                int newLevel = (curSkill == null ? 1 : curSkill.point + 1);

                                Skill newSkill = SkillUtil.createSkill(
                                        SkillUtil.getTempSkillSkillByItemID(player.LearnSkill.ItemTemplateSkillId),
                                        newLevel
                                );

                                if (curSkill == null) {
                                    player.BoughtSkill.add((int) skillId);

                                }
                                SkillUtil.setSkill(player, newSkill);

// Reset thông tin học skill
                                player.LearnSkill.Time = -1;
                                player.LearnSkill.ItemTemplateSkillId = -1;
                                player.LearnSkill.Potential = 0;

// Gửi thông tin về client
                                var msg = Service.gI().messageSubCommand((byte) 62);
                                msg.writer().writeShort(newSkill.skillId);
                                player.sendMessage(msg);
                                msg.cleanup();

                                PlayerService.gI().sendInfoHpMpMoney(player);
                                Service.gI().point(player);

                                Logger.log("Player " + player.name + " learned skill " + skillId + " level " + newLevel + " with " + requiredPotential + " potential");
                            } catch (Exception e) {
                                Logger.log("Error learning skill: " + e.toString());
                                e.printStackTrace();
                            }
                        }
                    }

                    ArrayList<String> menu = new ArrayList<>();
                    menu.add("Nhiệm vụ");
                    menu.add("Học\nKỹ năng");
                    if (player.clan != null) {
                        menu.add("Về khu\nvực bang");
                        if (player.clan.isLeader(player)) {
                            menu.add("Giải tán\nBang hội");
                        }
                    }
                    menu.add("Kho báu\ndưới biển");
                    this.createOtherMenu(player, 0,
                            "Chào con, ta rất vui khi gặp con\nCon muốn làm gì nào ?",
                            menu.toArray(new String[0]));
                } else if (select == 2) {
                    Item ruacon = InventoryService.gI().findItemBag(player, 874);
                    if (ruacon != null && ruacon.quantity >= 1) {
                        this.createOtherMenu(player, 1,
                                "Cảm ơn cậu đã cứu con rùa của ta\nĐể cảm ơn ta sẽ tặng cậu món quà.",
                                "Nhận quà", "Đóng");
                    }
                }
                break;

            case 12:
                if (select == 1) {
                    this.createOtherMenu(player, 13, "Con có muốn huỷ học kỹ năng này và nhận lại 50% số tiềm năng không ?", "Đồng ý", "Từ Chối");
                } else if (select == 0) {
                    long time = player.LearnSkill.Time - System.currentTimeMillis();
                    int ngoc = 5;
                    if (time / 600_000 >= 2) {
                        ngoc += time / 600_000;
                    }
                    if (player.inventory.gem < ngoc) {
                        Service.gI().sendThongBao(player, "Bạn không có đủ ngọc");
                        return;
                    }
                    player.inventory.subGemAndRuby(ngoc);
                    player.LearnSkill.Time = -1;
                    try {
                        int skillId = player.LearnSkill.ItemTemplateSkillId;

                        // Lấy skill hiện tại và TĂNG LEVEL
                        Skill curSkill = SkillUtil.getSkillByItemID(player, skillId);
                        int currentLevel = curSkill.point;
                        int newLevel = currentLevel + 1; // ← TĂNG LEVEL Ở ĐÂY

                        // Kiểm tra template và level tối đa
                        var template = SkillUtil.getTempSkillSkillByItemID(skillId);

                        // Tạo skill mới với level đã tăng
                        Skill newSkill = SkillUtil.createSkill(template, newLevel);

                        // Cập nhật danh sách skill đã học (nếu là lần đầu)
                        if (currentLevel == 0) {
                            player.BoughtSkill.add(skillId);
                        }

                        // Set skill mới với level đã tăng
                        SkillUtil.setSkill(player, newSkill);

                        var msg = Service.gI().messageSubCommand((byte) 62);
                        msg.writer().writeShort(newSkill.skillId);
                        player.sendMessage(msg);
                        msg.cleanup();

                        PlayerService.gI().sendInfoHpMpMoney(player);

                        // Debug log

                    } catch (Exception e) {
                        Logger.log("Error in quick learn: " + e.toString());
                        e.printStackTrace();
                    }
                }
                break;

            case 13:
                if (select == 0) {
                    try {
                        long refund = player.LearnSkill.Potential / 2;
                        if (refund > 0) {
                            player.nPoint.tiemNang += refund;
                            Service.gI().sendThongBao(player,
                                    "Bạn đã huỷ học kỹ năng và nhận lại " + Util.numberToMoney(refund) + " tiềm năng");
                        } else {
                            Service.gI().sendThongBao(player, "Không có tiềm năng để hoàn lại");
                        }

                        player.LearnSkill.Time = -1;
                        player.LearnSkill.ItemTemplateSkillId = -1;
                        player.LearnSkill.Potential = 0;

                        Service.gI().point(player);
                        PlayerService.gI().sendInfoHpMpMoney(player);

                    } catch (Exception e) {
                        Logger.log("Lỗi khi huỷ học kỹ năng: " + e.getMessage());
                    }
                }
                break;

            case 0:
                switch (select) {
                    case 0 -> NpcService.gI().createTutorial(player, tempId, avartar,
                            player.playerTask.taskMain.subTasks.get(player.playerTask.taskMain.index).name);
                    case 1 -> {
                        if (player.LearnSkill.Time != -1) {
                            int ngoc = 5;
                            long time = player.LearnSkill.Time - System.currentTimeMillis();
                            if (time / 600_000 >= 2) {
                                ngoc += time / 600_000;
                            }
                            String[] subName = services.ItemService.gI()
                                    .getTemplate(player.LearnSkill.ItemTemplateSkillId).name.split("");
                            byte level = Byte.parseByte(subName[subName.length - 1]);

                            // Get skill template for display
                            int skillTemplateId = SkillUtil.getTempSkillSkillByItemID(player.LearnSkill.ItemTemplateSkillId);
                            var skillTemplate = SkillUtil.findSkillTemplate(skillTemplateId);
                            String skillName = (skillTemplate != null) ? skillTemplate.name : "Unknown Skill";

                            this.createOtherMenu(player, 12,
                                    "Con đang học kỹ năng\n" + skillName +
                                            " cấp " + level + "\nThời gian còn lại " + TimeUtil.getTime(time),
                                    "Học\nCấp tốc\n" + ngoc + " ngọc", "Huỷ", "Bỏ qua");
                        } else {
                            ShopService.gI().opendShop(player, "QUY_LAO", false);
                        }
                    }
                    case 2 -> {
                        if (player.clan != null) {
                            ChangeMapService.gI().changeMapNonSpaceship(player, 153, Util.nextInt(100, 200), 432);
                        }
                    }
                    case 3 -> {
                        if (player.clan != null && player.clan.isLeader(player)) {
                            createOtherMenu(player, 3, "Con có chắc muốn giải tán bang hội không?",
                                    "Đồng ý", "Từ chối");
                        }
                    }
                    case 4 -> {
                        if (player.clan != null && player.clan.BanDoKhoBau != null) {
                            this.createOtherMenu(player, ConstNpc.MENU_OPENED_DBKB,
                                    "Bang hội con đang ở hang kho báu cấp " + player.clan.BanDoKhoBau.level +
                                            "\ncon có muốn đi cùng họ không?", "Top\nBang hội",
                                    "Thành tích\nBang", "Đồng ý", "Từ chối");
                        } else {
                            this.createOtherMenu(player, ConstNpc.MENU_OPEN_DBKB,
                                    "Đây là bản đồ kho báu hải tặc tí hon\nCác con cứ yên tâm lên đường\nỞ đây có ta lo\nNhớ chọn cấp độ vừa sức mình nhé",
                                    "Top\nBang hội", "Thành tích\nBang", "Chọn\ncấp độ", "Từ chối");
                        }
                    }
                }
                break;

            case 3:
                if (player.clan != null && player.clan.isLeader(player) && select == 0) {
                    Input.gI().createFormGiaiTanBangHoi(player);
                }
                break;

            case ConstNpc.MENU_OPENED_DBKB:
                if (select == 2) {
                    if (player.clan == null) {
                        Service.gI().sendThongBao(player, "Hãy vào bang hội trước");
                    } else if (player.isAdmin() || player.nPoint.power >= TreasureUnderSea.POWER_CAN_GO_TO_DBKB) {
                        ChangeMapService.gI().goToDBKB(player);
                    } else {
                        this.npcChat(player, "Yêu cầu sức mạnh lớn hơn " +
                                Util.numberToMoney(TreasureUnderSea.POWER_CAN_GO_TO_DBKB));
                    }
                }
                break;

            case ConstNpc.MENU_OPEN_DBKB:
                if (select == 2) {
                    if (player.clan == null) {
                        Service.gI().sendThongBao(player, "Hãy vào bang hội trước");
                    } else if (player.isAdmin() || player.nPoint.power >= TreasureUnderSea.POWER_CAN_GO_TO_DBKB) {
                        Input.gI().createFormChooseLevelBDKB(player);
                    } else {
                        this.npcChat(player, "Yêu cầu sức mạnh lớn hơn " +
                                Util.numberToMoney(TreasureUnderSea.POWER_CAN_GO_TO_DBKB));
                    }
                }
                break;

            case ConstNpc.MENU_ACCEPT_GO_TO_BDKB:
                if (select == 0) {
                    TreasureUnderSeaService.gI().openBanDoKhoBau(player,
                            Byte.parseByte(String.valueOf(PLAYERID_OBJECT.get(player.id))));
                }
                break;
        }
    }
}