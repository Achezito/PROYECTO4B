import React, { useEffect, useState } from "react";
import {
  Dimensions,
  FlatList,
  StyleSheet,
  Text,
  TouchableOpacity,
  View,
} from "react-native";
import Icon from "react-native-vector-icons/MaterialCommunityIcons";
import { BASE_URL } from "../config";

export default function WarehouseDetails({ route, navigation }) {
  const { id, name } = route.params;
  const [subWarehouses, setSubWarehouses] = useState([]);
  const [menuVisible, setMenuVisible] = useState(false);

  useEffect(() => {
    fetchSubWarehouses();
  }, []);

  const fetchSubWarehouses = async () => {
    try {
      const response = await fetch(
        `${BASE_URL}/PROYECTO4B-1/phpfiles/react/sub_warehouse_api.php?id=${id}`
      );
      const data = await response.json();
      setSubWarehouses(data);
    } catch (error) {
      console.error("Error obteniendo subalmacenes:", error);
    }
  };

  return (
    <View style={styles.container}>
      <View style={styles.header}>
        <Text style={styles.title}>Detalles del Almacén</Text>
        <Text style={styles.subtitle}>{name}</Text>
        <View style={styles.headerDivider} />
      </View>

      <FlatList
        data={subWarehouses}
        contentContainerStyle={styles.listContent}
        keyExtractor={(item, index) => index.toString()}
        renderItem={({ item }) => (
          <TouchableOpacity
            style={styles.card}
            onPress={() =>
              navigation.navigate("SubWarehouseDetails", {
                id: item.ID,
                location: item.Location,
              })
            }
            onLongPress={() =>
              navigation.navigate("UpdateSubWarehouse", {
                id: item.ID,
                location: item.Location,
                capacity: item.Capacity,
                id_category: item.IdCategory,
                warehouseId: id,
              })
            }
          >
            <View style={styles.cardHeader}>
              <Icon name="warehouse" size={28} color="#FFF" />
              <Text style={styles.cardTitle}>Subalmacén #{item.ID}</Text>
            </View>
            <View style={styles.cardBody}>
              <View style={styles.infoRow}>
                <Icon name="map-marker" size={20} color="#FF6F00" />
                <Text style={styles.cardText}>Ubicación: {item.Location}</Text>
              </View>
              {item.Capacity && (
                <View style={styles.infoRow}>
                  <Icon name="cube-outline" size={20} color="#FF6F00" />
                  <Text style={styles.cardText}>
                    Capacidad: {item.Capacity}
                  </Text>
                </View>
              )}
            </View>
          </TouchableOpacity>
        )}
        ListEmptyComponent={
          <View style={styles.emptyContainer}>
            <Icon name="warehouse-off" size={50} color="#FFA040" />
            <Text style={styles.emptyText}>
              No hay subalmacenes registrados
            </Text>
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
              navigation.navigate("AddSubWarehouse", { warehouseId: id });
            }}
            activeOpacity={0.7}
          >
            <Icon name="plus-circle" size={24} color="#FF6F00" />
            <Text style={styles.menuText}>Añadir Subalmacén</Text>
          </TouchableOpacity>
          <View style={styles.menuDivider} />
          <TouchableOpacity
            style={styles.menuItem}
            onPress={() => {
              setMenuVisible(false);
              navigation.navigate("UpdateSubWarehouse", { warehouseId: id });
            }}
            activeOpacity={0.7}
          >
            <Icon name="pencil" size={24} color="#FF6F00" />
            <Text style={styles.menuText}>Actualizar Subalmacén</Text>
          </TouchableOpacity>
          <View style={styles.menuDivider} />
          <TouchableOpacity
            style={styles.menuItem}
            onPress={() => {
              setMenuVisible(false);
              navigation.navigate("RecepcionScreen", { warehouseId: id });
            }}
            activeOpacity={0.7}
          >
            <Icon name="truck-delivery" size={24} color="#FF6F00" />
            <Text style={styles.menuText}>Recepción de Materiales</Text>
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
    marginBottom: 20,
    alignItems: "center",
  },
  title: {
    fontSize: 24,
    fontWeight: "700",
    color: "#E65100",
    textAlign: "center",
  },
  subtitle: {
    fontSize: 20,
    color: "#FF6F00",
    textAlign: "center",
    marginTop: 5,
    fontWeight: "600",
  },
  headerDivider: {
    height: 3,
    width: "30%",
    backgroundColor: "#FFA040",
    marginTop: 15,
    borderRadius: 3,
  },
  listContent: {
    paddingBottom: 20,
  },
  card: {
    backgroundColor: "white",
    borderRadius: 12,
    padding: 15,
    marginBottom: 15,
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
  cardText: {
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
    minWidth: 220,
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
    marginTop: 50,
  },
  emptyText: {
    fontSize: 18,
    color: "#FFA040",
    marginTop: 15,
    fontWeight: "500",
  },
});
