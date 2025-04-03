import React, { useEffect, useState } from "react";
import { BASE_URL } from "../config";
import {
  Dimensions,
  FlatList,
  StyleSheet,
  Text,
  TouchableOpacity,
  View,
} from "react-native";
import Icon from "react-native-vector-icons/MaterialCommunityIcons";

export default function InterfazWarehouse({ navigation }) {
  const [warehouses, setWarehouses] = useState([]);
  const [groupedWarehouses, setGroupedWarehouses] = useState([]);
  const [menuVisible, setMenuVisible] = useState(false);

  useEffect(() => {
    getWarehouses();
  }, []);

  const getWarehouses = async () => {
    try {
      const response = await fetch(
        `${BASE_URL}/PROYECTO4B-1/phpfiles/react/warehouse_api.php`
      );
      const data = await response.json();
      console.log("Almacenes ", data);
      setWarehouses(data);

      const groupedData = [];
      for (let i = 0; i < data.length; i += 2) {
        groupedData.push(data.slice(i, i + 2));
      }
      setGroupedWarehouses(groupedData);
    } catch (error) {
      console.error("Error obteniendo almacenes:", error);
    }
  };

  return (
    <View style={styles.container}>
      <View style={styles.header}>
        <Text style={styles.title}>Gestión de Almacenes</Text>
        <View style={styles.headerDivider} />
      </View>

      <FlatList
        data={groupedWarehouses}
        contentContainerStyle={styles.listContent}
        renderItem={({ item }) => (
          <View style={styles.row}>
            {item.map((warehouse) => (
              <TouchableOpacity
                key={warehouse.id_warehouse}
                style={styles.card}
                onPress={() =>
                  navigation.navigate("WarehouseDetails", {
                    id: warehouse.id_warehouse,
                    name: warehouse.name,
                  })
                }
              >
                <View style={styles.cardHeader}>
                  <Icon name="warehouse" size={28} color="#FFF" />
                  <Text style={styles.cardTitle}>{warehouse.name}</Text>
                </View>
                <View style={styles.cardBody}>
                  <View style={styles.infoRow}>
                    <Icon name="cube-outline" size={20} color="#FF6F00" />
                    <Text style={styles.cardSubtitle}>
                      Capacidad: {warehouse.capacity}
                    </Text>
                  </View>
                  <View style={styles.infoRow}>
                    <Icon name="map-marker" size={20} color="#FF6F00" />
                    <Text style={styles.cardSubtitle}>
                      Ubicación: {warehouse.location}
                    </Text>
                  </View>
                </View>
              </TouchableOpacity>
            ))}
          </View>
        )}
        keyExtractor={(item, index) => index.toString()}
        ListEmptyComponent={
          <View style={styles.emptyContainer}>
            <Icon name="warehouse" size={50} color="#FFA040" />
            <Text style={styles.emptyText}>No hay almacenes registrados</Text>
          </View>
        }
      />

      <TouchableOpacity
        style={styles.menuButton}
        onPress={() => setMenuVisible(!menuVisible)}
        activeOpacity={0.8}
      >
        <Icon name={menuVisible ? "close" : "plus"} size={30} color="white" />
      </TouchableOpacity>

      {menuVisible && (
        <View style={styles.menu}>
          <TouchableOpacity
            style={styles.menuItem}
            onPress={() => {
              setMenuVisible(false);
              navigation.navigate("CreateWarehouse");
            }}
            activeOpacity={0.7}
          >
            <Icon name="plus-circle" size={24} color="#FF6F00" />
            <Text style={styles.menuText}>Añadir Almacén</Text>
          </TouchableOpacity>
          <View style={styles.menuDivider} />
          <TouchableOpacity
            style={styles.menuItem}
            onPress={() => {
              setMenuVisible(false);
              navigation.navigate("UpdateWarehouse");
            }}
            activeOpacity={0.7}
          >
            <Icon name="pencil" size={24} color="#FF6F00" />
            <Text style={styles.menuText}>Actualizar Almacén</Text>
          </TouchableOpacity>
        </View>
      )}
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: "#FFF8F0",
    paddingHorizontal: 15,
    paddingTop: 20,
  },
  header: {
    marginBottom: 25,
    alignItems: "center",
  },
  headerDivider: {
    height: 3,
    width: "30%",
    backgroundColor: "#FFA040",
    marginTop: 10,
    borderRadius: 3,
  },
  title: {
    fontSize: 26,
    fontWeight: "700",
    color: "#E65100",
    textAlign: "center",
  },
  listContent: {
    paddingBottom: 20,
  },
  row: {
    flexDirection: "row",
    justifyContent: "space-between",
    marginBottom: 15,
  },
  card: {
    flex: 1,
    backgroundColor: "white",
    borderRadius: 12,
    padding: 15,
    marginHorizontal: 5,
    shadowColor: "#FFA040",
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.2,
    shadowRadius: 8,
    elevation: 6,
    borderWidth: 1,
    borderColor: "#FFE0B2",
  },
  cardHeader: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "#FF6F00",
    padding: 10,
    borderRadius: 8,
    marginBottom: 12,
    marginTop: -5,
    marginHorizontal: -5,
  },
  cardTitle: {
    fontSize: 18,
    fontWeight: "bold",
    color: "white",
    marginLeft: 10,
  },
  cardBody: {
    paddingHorizontal: 5,
  },
  infoRow: {
    flexDirection: "row",
    alignItems: "center",
    marginBottom: 8,
  },
  cardSubtitle: {
    fontSize: 15,
    color: "#5D4037",
    marginLeft: 8,
  },
  menuButton: {
    position: "absolute",
    bottom: 30,
    right: 25,
    backgroundColor: "#FF6F00",
    width: 60,
    height: 60,
    borderRadius: 30,
    justifyContent: "center",
    alignItems: "center",
    shadowColor: "#000",
    shadowOffset: { width: 0, height: 3 },
    shadowOpacity: 0.3,
    shadowRadius: 5,
    elevation: 8,
  },
  menu: {
    position: "absolute",
    bottom: 100,
    right: 25,
    backgroundColor: "white",
    borderRadius: 12,
    paddingVertical: 10,
    shadowColor: "#000",
    shadowOffset: { width: 0, height: 3 },
    shadowOpacity: 0.3,
    shadowRadius: 5,
    elevation: 8,
    minWidth: 200,
  },
  menuItem: {
    flexDirection: "row",
    alignItems: "center",
    paddingVertical: 12,
    paddingHorizontal: 20,
  },
  menuText: {
    fontSize: 16,
    color: "#FF6F00",
    fontWeight: "600",
    marginLeft: 15,
  },
  menuDivider: {
    height: 1,
    backgroundColor: "#FFE0B2",
    marginVertical: 5,
  },
  emptyContainer: {
    flex: 1,
    justifyContent: "center",
    alignItems: "center",
    marginTop: 100,
  },
  emptyText: {
    fontSize: 18,
    color: "#FFA040",
    marginTop: 15,
    fontWeight: "500",
  },
});
