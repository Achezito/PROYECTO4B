import React, { useRef, useState, useEffect } from 'react';
import { Animated, ScrollView, StyleSheet, Text, TouchableOpacity, View, Dimensions } from 'react-native';
import { PieChart } from 'react-native-chart-kit'; // Importar PieChart
import Icon from 'react-native-vector-icons/MaterialIcons';
import IconsFont from 'react-native-vector-icons/FontAwesome';

export default function Dashboard({ navigation }) {
  const [menuOpen, setMenuOpen] = useState(false);
  const [pieChartData, setPieChartData] = useState([]); // Estado para los datos de la gráfica
  const { width } = Dimensions.get('window');
  const translateX = useRef(new Animated.Value(-width)).current;

  const toggleMenu = () => {
    Animated.timing(translateX, {
      toValue: menuOpen ? -width : 0,
      duration: 300,
      useNativeDriver: true,
    }).start();
    setMenuOpen(!menuOpen);
  };

  const sections = [
    {title: 'Dashboard', icon: 'bar-chart', screen: 'Dashboard'},
    {title: 'Recepcion', icon: 'inbox', screen: 'RecepcionScreen'},
    { title: 'Proveedores', icon: 'local-shipping', screen: 'AllProovedores' },
    { title: 'Suministros', icon: 'inventory', screen: 'AllSuministros' },
    { title: 'Almacenes', icon: 'store', screen: 'InterfazWarehouse' },
    { title: 'Órdenes', icon: 'assignment', screen: 'AllOrders' },
    { title: 'Reportes', icon: 'bar-chart', screen: 'AllTransacciones' },
    { title: 'Configuración', icon: 'settings', screen: 'SettingsScreen' },
  ];

  useEffect(() => {
    fetch('http://localhost/PROYECTO4B-1/phpfiles/react/sub_warehouse_api.php?distribution=true')
      .then(response => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .then(data => {
        console.log("Datos obtenidos de la API:", data); // Verifica los datos en la consola
        const chartData = data.map(item => ({
          name: item.sub_warehouse,
          population: Number(item.total_quantity), // Convertir a número
          color: getRandomColor(), // Generar colores dinámicos
          legendFontColor: '#333',
          legendFontSize: 14,
        }));
        console.log("Datos para la gráfica:", chartData); // Verifica los datos para la gráfica
        setPieChartData(chartData);
      })
      .catch(error => console.error('Error al obtener los datos:', error));
  }, []);

  // Función para generar colores aleatorios
 // Función para generar colores aleatorios con tonos suaves
const getRandomColor = () => {
  const r = Math.floor(Math.random() * 156) + 100; // Rango: 100-255
  const g = Math.floor(Math.random() * 156) + 100; // Rango: 100-255
  const b = Math.floor(Math.random() * 156) + 100; // Rango: 100-255
  return `rgb(${r}, ${g}, ${b})`;
};

  return (
    <View style={styles.container}>
      {/* Botón para abrir el menú */}
      {!menuOpen && (
        <TouchableOpacity onPress={toggleMenu} style={styles.menuButton}>
          <Icon name="menu" size={30} color="white" />
        </TouchableOpacity>
      )}

      {/* Menú lateral animado */}
      <Animated.View style={[styles.sidebar, { transform: [{ translateX }] }]}>
        <ScrollView contentContainerStyle={styles.sidebarContent}>
          <TouchableOpacity onPress={toggleMenu} style={styles.closeButton}>
            <IconsFont name="close" size={30} color="black" />
          </TouchableOpacity>
          <Text style={styles.sidebarTitle}>Menú</Text>
          {sections.map((section, index) => (
            <TouchableOpacity
              key={index}
              style={styles.sidebarItem}
              onPress={() => {
                toggleMenu();
                navigation.navigate(section.screen);
              }}
            >
              <Icon name={section.icon} size={25} color="#FF6F00" />
              <Text style={styles.sidebarItemText}>{section.title}</Text>
            </TouchableOpacity>
          ))}
        </ScrollView>
      </Animated.View>

      {/* Contenido principal del Dashboard */}
      <ScrollView contentContainerStyle={styles.dashboardContent}>
        <Text style={styles.dashboardTitle}>Dashboard</Text>

        {/* Contenedor para la gráfica de pastel */}
        <View style={styles.chartContainer}>
          <Text style={styles.chartTitle}>Material por Sub almacen</Text>
          {pieChartData.length > 0 ? (
          <PieChart
          data={pieChartData}
          width={Dimensions.get('window').width - 40} // Ancho dinámico
          height={220}
          chartConfig={{
            color: (opacity = 1) => `rgba(255, 111, 0, ${opacity})`,
          }}
          accessor="population" // Asegúrate de que este campo coincida con los datos
          backgroundColor="transparent"
          paddingLeft="15"
          absolute
        />
          ) : (
            <Text>Cargando datos...</Text>
          )}
        </View>

        {/* Grid de secciones */}
        <View style={styles.grid}>
          {sections.map((section, index) => (
            <TouchableOpacity
              key={index}
              style={styles.card}
              onPress={() => navigation.navigate(section.screen)}
            >
              <Icon name={section.icon} size={50} color="#FF6F00" />
              <Text style={styles.cardText}>{section.title}</Text>
            </TouchableOpacity>
          ))}
        </View>
      </ScrollView>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#FFF3E0',
  },
  menuButton: {
    position: 'absolute',
    top: 50,
    left: 20,
    backgroundColor: '#FF6F00',
    padding: 10,
    borderRadius: 5,
    zIndex: 2,
  },
  sidebar: {
    position: 'absolute',
    top: 0,
    left: 0,
    width: '80%',
    height: '100%',
    backgroundColor: '#FFF8E1',
    zIndex: 1,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.2,
    shadowRadius: 5,
    elevation: 5,
  },
  sidebarContent: {
    flexGrow: 1,
    padding: 20,
    paddingTop: 50,
  },
  closeButton: {
    position: 'absolute',
    top: 10,
    right: 10,
    padding: 10,
  },
  sidebarTitle: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#FF6F00',
    marginBottom: 20,
  },
  sidebarItem: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingVertical: 10,
    borderBottomWidth: 1,
    borderBottomColor: '#FFCC80',
  },
  sidebarItemText: {
    marginLeft: 10,
    fontSize: 16,
    color: '#333',
  },
  dashboardContent: {
    flexGrow: 1,
    padding: 20,
    alignItems: 'center',
  },
  dashboardTitle: {
    fontSize: 28,
    fontWeight: 'bold',
    color: '#FF6F00',
    marginBottom: 20,
  },
  chartContainer: {
    width: '100%',
    backgroundColor: '#FFFFFF',
    borderRadius: 10,
    padding: 20,
    marginBottom: 20,
    alignItems: 'center',
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 5,
    elevation: 5,
  },
  chartTitle: {
    fontSize: 20,
    fontWeight: 'bold',
    color: '#FF6F00',
    marginBottom: 10,
  },
  grid: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    justifyContent: 'space-between',
  },
  card: {
    width: '45%',
    backgroundColor: '#FFFFFF',
    borderRadius: 10,
    padding: 20,
    marginBottom: 20,
    alignItems: 'center',
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 5,
    elevation: 5,
  },
  cardText: {
    marginTop: 10,
    fontSize: 16,
    fontWeight: 'bold',
    color: '#FF6F00',
    textAlign: 'center',
  },
});