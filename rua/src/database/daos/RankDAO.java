package database.daos;

import java.sql.*;
import java.util.ArrayList;
import java.util.List;
import database.AlyraManager;
import models.player.Player;
import models.rank.RankInfo;
import org.json.simple.JSONArray;
import org.json.simple.JSONValue;
import models.task.TaskMain;
import models.task.SubTaskMain;
import utils.Logger;

public class RankDAO {

    public static final List<TaskMain> TASKS = new ArrayList<>();

    public static void loadTasks() {
        TASKS.clear();
        String sql = "SELECT task_main_template.id AS main_id, " +
                "task_main_template.name AS main_name, " +
                "task_main_template.detail, " +
                "task_sub_template.name AS sub_name, " +
                "task_sub_template.max_count, " +
                "task_sub_template.notify, " +
                "task_sub_template.npc_id, " +
                "task_sub_template.map " +
                "FROM task_main_template " +
                "JOIN task_sub_template ON task_main_template.id = task_sub_template.task_main_id";

        try (Connection conn = AlyraManager.getConnection();
             PreparedStatement ps = conn.prepareStatement(sql);
             ResultSet rs = ps.executeQuery()) {

            int lastTaskId = -1;
            TaskMain task = null;

            while (rs.next()) {
                int mainId = rs.getInt("main_id");

                if (mainId != lastTaskId) {
                    lastTaskId = mainId;
                    task = new TaskMain();
                    task.id = mainId;
                    task.name = rs.getString("main_name");
                    task.detail = rs.getString("detail");
                    task.subTasks = new ArrayList<>();
                    TASKS.add(task);
                }

                SubTaskMain subTask = new SubTaskMain();
                subTask.name = rs.getString("sub_name");
                subTask.maxCount = rs.getShort("max_count");
                subTask.notify = rs.getString("notify");
                subTask.npcId = rs.getByte("npc_id");
                subTask.mapId = rs.getShort("map");

                if (task != null) {
                    task.subTasks.add(subTask);
                }
            }

        } catch (Exception e) {
            e.printStackTrace();
        }
    }




    public static List<RankInfo> getTopSucManh(int limit) {
        List<RankInfo> list = new ArrayList<>();
        String sql = "SELECT id, name, data_point " +
                "FROM player " +
                "ORDER BY CAST(JSON_UNQUOTE(JSON_EXTRACT(data_point, '$[1]')) AS UNSIGNED) DESC " +
                "LIMIT ?";

        try (Connection con = AlyraManager.getConnection();
             PreparedStatement ps = con.prepareStatement(sql)) {
            ps.setInt(1, limit);
            ResultSet rs = ps.executeQuery();
            int rank = 1;
            while (rs.next()) {
                RankInfo info = new RankInfo();
                info.setId(rs.getLong("id"));
                info.setRank(rank++);
                info.setName(rs.getString("name"));

                // Lấy sức mạnh từ data_point
                String dataPoint = rs.getString("data_point");
                JSONArray arrPoint = (JSONArray) JSONValue.parse(dataPoint);
                if (arrPoint != null && arrPoint.size() > 1) {
                    long power = Long.parseLong(arrPoint.get(1).toString());
                    info.setPower(power);
                }

                info.setInfo("Sức mạnh: " + info.getPower());
                list.add(info);
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
        return list;
    }

    /**
     * Lấy top nhiệm vụ
     */
    public static List<RankInfo> getTopTask(int limit) {
        List<RankInfo> list = new ArrayList<>();
        String sql = "SELECT id, name, data_point, data_task FROM player " +
                "ORDER BY CAST(JSON_UNQUOTE(JSON_EXTRACT(data_task, '$[1]')) AS UNSIGNED) DESC " +
                "LIMIT ?";

        try (Connection con = AlyraManager.getConnection();
             PreparedStatement ps = con.prepareStatement(sql)) {

            ps.setInt(1, limit);
            ResultSet rs = ps.executeQuery();
            int rank = 1;

            while (rs.next()) {
                RankInfo info = new RankInfo();
                info.setId(rs.getLong("id"));
                info.setRank(rank++);
                info.setName(rs.getString("name"));


                String dataPoint = rs.getString("data_point");
                JSONArray arrPoint = (JSONArray) JSONValue.parse(dataPoint);
                if (arrPoint != null && arrPoint.size() > 1) {
                    long power = Long.parseLong(arrPoint.get(1).toString());
                    info.setPower(power);
                }
                // parse data_task
                String dataTask = rs.getString("data_task");
                if (dataTask != null && !dataTask.isEmpty()) {
                    JSONArray arrTask = (JSONArray) JSONValue.parse(dataTask);
                    if (arrTask != null && arrTask.size() > 1) {
                        int taskId = Integer.parseInt(arrTask.get(0).toString());
                        int taskIndex = Integer.parseInt(arrTask.get(1).toString());
                        info.setTaskId(taskId);
                        info.setTaskIndex(taskIndex);

                        // Lấy tên nhiệm vụ từ TASKS
                        String taskName = getTaskNameById(taskId);
                        info.setInfo("Nhiệm vụ: " + taskName + "\n" + "Sức mạnh: " + info.getPower());
                    }
                }

                list.add(info);
            }

            // Sắp xếp giảm dần theo taskIndex
            list.sort((a, b) -> Integer.compare(b.getTaskIndex(), a.getTaskIndex()));

            // Gán rank và giới hạn top
            for (int i = 0; i < Math.min(limit, list.size()); i++) {
                list.get(i).setRank(i + 1);
            }

        } catch (Exception e) {
            e.printStackTrace();
        }

        return list;
    }

    /**
     * Lấy tên nhiệm vụ từ taskId
     */
    private static String getTaskNameById(int taskId) {
        for (TaskMain task : TASKS) {
            if (task.id == taskId) {
                return task.name;
            }
        }
        return "Unknown Task";
    }

}
