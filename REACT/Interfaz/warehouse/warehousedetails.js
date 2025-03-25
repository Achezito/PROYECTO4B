import React, { useEffect, useState } from 'react';
import { Dimensions, FlatList, StyleSheet, Text, TouchableOpacity, View } from 'react-native';
import Icon from 'react-native-vector-icons/MaterialCommunityIcons';

export default function WarehouseDetails({ route, navigation }) {
  const { id, name } = route.params; // Recibe el ID y el nombre del almacén desde la navegación
  const [subWarehouses, setSubWarehouses] = useState([]); // Lista de subalmacenes
  const [menuVisible, setMenuVisible] = useState(false); // Controla la visibilidad del menú

  useEffect(() => {
    fetchSubWarehouses(); // Carga los subalmacenes al montar el componente
  }, []);

  const fetchSubWarehouses = async () => {
    try {
      const response = await fetch(`http://localhost/PROYECTO4B-1/phpfiles/react/sub_warehouse_api.php?id=${id}`);
      const data = await response.json();
      setSubWarehouses(data);
    } catch (error) {
      console.error('Error obteniendo subalmacenes:', error);
    }
  };

  return (
    <View style={styles.container}>
      {/* Menú tipo hamburguesa */}
      <TouchableOpacity
        style={styles.menuButton}
        onPress={() => setMenuVisible(!menuVisible)} // Alterna la visibilidad del menú
      >
        <Icon name="plus" size={40} color="white" />
      </TouchableOpacity>

      {menuVisible && (
        <View style={styles.menu}>
          <TouchableOpacity
            style={styles.menuItem}
            onPress={() => {
              setMenuVisible(false);
              navigation.navigate('AddSubWarehouse', { warehouseId: id }); // Navega a la pantalla para añadir un subalmacén
            }}
          >
            <Text style={styles.menuText}>Añadir Subalmacén</Text>
          </TouchableOpacity>
          <TouchableOpacity
            style={styles.menuItem}
            onPress={() => {
              setMenuVisible(false);
              navigation.navigate('UpdateSubWarehouse', { warehouseId: id }); // Navega directamente a la pantalla de actualización
            }}
          >
            <Text style={styles.menuText}>Actualizar Subalmacén</Text>
          </TouchableOpacity>
        </View>
      )}
      
      <Text style={styles.title}>Almacén: {name}</Text>
{/* estilo anterior
  <FlatList
        data={subWarehouses}
        keyExtractor={(item, index) => index.toString()}
        numColumns={2}
        columnWrapperStyle={styles.row}
        renderItem={({ item }) => (
          <TouchableOpacity
            style={styles.card}
            onPress={() =>
              navigation.navigate('SubWarehouseDetails', {
                id: item.ID,
                location: item.Location,
              })
            }
            onLongPress={() =>
              navigation.navigate('UpdateSubWarehouse', {
                id: item.ID,
                location: item.Location,
                capacity: item.Capacity,
                id_category: item.IdCategory,
                warehouseId: id,
              })
            }
          >
*/}
      <FlatList
        data={subWarehouses}
        keyExtractor={(item, index) => index.toString()}
        renderItem={({ item }) => (
          <TouchableOpacity
            style={styles.card}
            onPress={() =>
              navigation.navigate('SubWarehouseDetails', {
                id: item.ID,
                location: item.Location,
              })
            }
            onLongPress={() =>
              navigation.navigate('UpdateSubWarehouse', {
                id: item.ID,
                location: item.Location,
                capacity: item.Capacity,
                id_category: item.IdCategory,
                warehouseId: id,
              })
            }
          >
            <Icon name="warehouse" size={40} color="rgb(42, 126, 209)" style={styles.icon} />
            <Text style={styles.cardTitle}>Subalmacén ID: {item.ID}</Text>
            <Text style={styles.cardText}>Ubicación: {item.Location}</Text>
          </TouchableOpacity>
        )}
        ListEmptyComponent={
          <View style={styles.emptyContainer}>
            <Icon name="warehouse-off" size={50} color="white" />
            <Text style={styles.emptyText}>No hay subalmacenes disponibles</Text>
          </View>
        }
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: 'rgb(33, 37, 41)', // Fondo oscuro
    padding: 20,
    display: 'flex',
    alignItems: 'center'
  },
  menuButton: {
    position: 'absolute',
      top: Dimensions.get('window').height - 160,
     left: Dimensions.get('window').width - 90,
    zIndex: 10,
    backgroundColor: 'rgb(42, 126, 209)',
    borderRadius: 50,
    padding: 15,
    fontSize: 50
  },
  menu: {
    position: 'absolute',
    top: Dimensions.get('window').height - 280,
     left: Dimensions.get('window').width - 240,
    backgroundColor: 'white',
    borderRadius: 10,
    padding: 10,
    zIndex: 10,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.3,
    shadowRadius: 5,
    elevation: 5,
  },
  menuItem: {
    paddingVertical: 10,
    paddingHorizontal: 20,
  },
  menuText: {
    fontSize: 16,
    color: 'rgb(33, 37, 41)',
  },
  title: {
    fontSize: 24,
    fontWeight: 'bold',
    color: 'white',
    marginBottom: 16,
    textAlign: 'center',
  },
  row: {
    justifyContent: 'space-between',
    marginBottom: 15,
  },/* estilo anterior
  card: {
    backgroundColor: 'white',
    borderRadius: 15, // Bordes más redondeados
    padding: 20,
    marginBottom: 15,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.3,
    shadowRadius: 5,
    elevation: 6,
    width: Dimensions.get('window').width / 2 - 30,
    alignItems: 'center', // Centra el contenido
  },*/
  card: {
    backgroundColor: 'white',
    borderRadius: 15, // Bordes más redondeados
    padding: 20,
    marginBottom: 15,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.3,
    shadowRadius: 5,
    elevation: 6,
    width: Dimensions.get('window').width - 80,
    alignItems: 'center', // Centra el contenido
  },
  icon: {
    marginBottom: 10,
  },
  cardTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: 'rgb(42, 126, 209)',
    marginBottom: 8,
    textAlign: 'center',
  },
  cardText: {
    fontSize: 14,
    color: 'rgb(33, 37, 41)',
    textAlign: 'center',
  },
  emptyContainer: {
    alignItems: 'center',
    marginTop: 50,
  },
  emptyText: {
    fontSize: 18,
    color: 'white',
    textAlign: 'center',
    marginTop: 10,
  },
});