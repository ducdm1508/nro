package database.daos;


import database.AlyraManager;
import database.AlyraResultSet;
import models.player.Traning;
import models.tournament.super_rank.SuperRankBuilder;
import org.json.simple.JSONArray;
import models.player.Player;
import org.json.simple.JSONObject;
import org.json.simple.JSONValue;
import org.json.simple.parser.JSONParser;
import utils.TimeUtil;
import utils.Util;

import java.util.ArrayList;
import java.util.Collections;
import java.util.List;

public class TraningDAO {

    public static void updatePlayer(Player player) {
        if (player != null && player.idMark.isLoadedAllDataPlayer()) {
            try {
                JSONArray dataArray = new JSONArray();
                dataArray.add(player.levelLuyenTap);
                dataArray.add(player.dangKyTapTuDong);
                dataArray.add(player.mapIdDangTapTuDong);
                dataArray.add(player.tnsmLuyenTap);
                if (player.isOffline) {
                    dataArray.add(player.lastTimeOffline);
                } else {
                    dataArray.add(System.currentTimeMillis());
                }
                dataArray.add(player.traning.getTop());
                dataArray.add(player.traning.getTime());
                dataArray.add(player.traning.getLastTime());
                dataArray.add(player.traning.getLastTop());
                dataArray.add(player.traning.getLastRewardTime());

                String dataLuyenTap = dataArray.toJSONString();
                dataArray.clear();

                String query = "UPDATE player SET data_luyentap = ? WHERE id = ?";
                AlyraManager.executeUpdate(query, dataLuyenTap, player.id);
            } catch (Exception e) {
                e.printStackTrace();
            }
        }
    }

    public static void loadData(Player player){
        try {
            AlyraResultSet rs = AlyraManager.executeQuery("SELECT id, name, data_luyentap FROM player WHERE id = " + player.id);
            JSONArray dataArray;
            player.id = rs.getInt("id");
            player.name = rs.getString("name");
            dataArray = (JSONArray) JSONValue.parse(rs.getString("data_luyentap"));
            player.levelLuyenTap = Integer.parseInt(dataArray.get(0).toString());
            player.dangKyTapTuDong = Boolean.parseBoolean(dataArray.get(1).toString());
            player.mapIdDangTapTuDong = Integer.parseInt(dataArray.get(2).toString());
            player.tnsmLuyenTap = Integer.parseInt(dataArray.get(3).toString());
            player.lastTimeOffline = Long.parseLong(dataArray.get(4).toString());
            if (dataArray.size() > 5) {
                player.traning.setTop(Integer.parseInt(dataArray.get(5).toString()));
                player.traning.setTime(Integer.parseInt(dataArray.get(6).toString()));
                player.traning.setLastTime(Long.parseLong(dataArray.get(7).toString()));
                player.traning.setLastTop(Integer.parseInt(dataArray.get(8).toString()));
                player.traning.setLastRewardTime(Long.parseLong(dataArray.get(9).toString()));
            }
        } catch (Exception e) {
            player.levelLuyenTap = 0;
            player.dangKyTapTuDong = false;
            player.mapIdDangTapTuDong = -1;
            player.tnsmLuyenTap = 0;
            player.lastTimeOffline = System.currentTimeMillis();
        }
    }
    public static List<Traning> getTopTraning() {
        List<Traning> list = new ArrayList<>();
        try {
            int rank = 1; // Bắt đầu từ top 1
            AlyraResultSet rs = AlyraManager.executeQuery(
                    "SELECT id, name, head, data_luyentap FROM player " +
                            "ORDER BY JSON_EXTRACT(data_luyentap, '$[5]') DESC, " +
                            "JSON_EXTRACT(data_luyentap, '$[6]') DESC LIMIT 100;"
            );

            while (rs.next()) {
                Traning traning = readData(rs);
                traning.setTop(traning.getTop());
                traning.setTopWhis(rank); // TopWhis dựa trên thứ tự level
                list.add(traning);
                rank++; // Tăng rank
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
        return list;
    }

    public static Traning readData(AlyraResultSet rs) throws Exception {

        Traning traning = new Traning();

        if (rs != null) {
            JSONArray array = (JSONArray) JSONValue.parse(rs.getString("data_luyentap"));
            traning.setPlayerID(rs.getInt("id"));
            traning.setName(rs.getString("name"));
            traning.setHead(rs.getInt("head"));
            traning.setTop(Integer.parseInt(array.get(5).toString()));
            traning.setTime(Integer.parseInt(array.get(6).toString()));
            traning.setLastTime(Long.parseLong(array.get(7).toString()));
            traning.setLastTop(Integer.parseInt(array.get(8).toString()));
            traning.setLastRewardTime(Long.parseLong(array.get(9).toString()));

        }
        return traning;
    }

}
